
function loadData(sortOrder = 'desc') {
    $.ajax({
        url: "/combined-rates",
        type: "GET",
        data: { sort: sortOrder },
        dataType: "json",
        success: function(response) {
            $("#card-container").empty();
            
         
            const checkedTypes = ['forklift', 'oceanfreight', 'trucking', 'projectrate']
                .filter(type => $(`input[name="${type}"]`).is(':checked'))
                .map(type => type === 'oceanfreight' ? 'ocean' : 
                             type === 'projectrate' ? 'project' : type);
            
           
            const statusFilter = $('input[name="status"]:checked').val();
            
            
            const requestByMe = $('input[name="requestByMe"]').is(':checked');
            const repliedByMe = $('input[name="repliedByMe"]').is(':checked');
            
           
            let filteredData = [...(response.ocean || []), 
                              ...(response.forklift || []), 
                              ...(response.trucking || []), 
                              ...(response.project || [])];
            
            
            if (checkedTypes.length > 0) {
                filteredData = filteredData.filter(item => {
                    const itemType = 
                        item.ocean_created_at ? 'ocean' : 
                        item.forklift_created_at ? 'forklift' : 
                        item.trucking_created_at ? 'trucking' : 
                        item.rate_created_at ? 'project' : '';
                    
                    return checkedTypes.includes(itemType);
                });
            }
            
            
            if (statusFilter !== 'all') {
                filteredData = filteredData.filter(item => {
                    const status = item.ocean_status || item.forklift_status || 
                                  item.trucking_status || item.rate_status || '';
                    return status.toLowerCase() === statusFilter.toLowerCase();
                });
            }
            
            
     
if (requestByMe) {
    console.log("Filtering requests by:", currentUser);
    filteredData = filteredData.filter(item => {
        const from = item.ocean_from || item.forklift_from || 
                   item.trucking_from || item.rate_from || '';
        console.log("Checking item:", from);
        return from.trim().toLowerCase() === currentUser;
    });
}

 
if (repliedByMe) {
    filteredData = filteredData.filter(item => {
        return item.replied_by?.trim().toLowerCase() === currentUser;
    });
}

    
            filteredData.sort((a, b) => {
                const dateA = new Date(a.ocean_created_at || a.forklift_created_at || 
                                     a.trucking_created_at || a.rate_created_at);
                const dateB = new Date(b.ocean_created_at || b.forklift_created_at || 
                                     b.trucking_created_at || b.rate_created_at);
                return sortOrder === 'asc' ? dateA - dateB : dateB - dateA;
            });
  
            filteredData.forEach(item => {
                const types = {
                    ocean_created_at: ['Ocean Freight', 'ocean'],
                    forklift_created_at: ['Forklift Rental', 'forklift'],
                    trucking_created_at: ['Trucking Rate', 'trucking'],
                    rate_created_at: ['Rate Project', 'rate']
                };

                for (const [key, [type, prefix]] of Object.entries(types)) {
                    if (item[key]) {
                        createCard(item, type, `${prefix}_status`, `${prefix}_subject`, 
                                  `${prefix}_from`, key, item.replied_by);
                        break;
                    }
                }
            });
            
            updateSortButtonState(sortOrder);
            
   
           
        },
        error: xhr => console.error("Error:", xhr)
    });
}

function createCard(item, type, statusField, subjectField, fromField, createdField) {
    const statusClass = item[statusField] === "OPEN" ? "open-status" : item[statusField] === "CLOSED" ? "closed-status" : "default-status";
    const cardHtml = `
        <div class="card" data-id="${item.id}" data-type="${type.toLowerCase().replace(' ', '-')}">
            <div class="cardtitle">${item[subjectField] || 'N/A'}</div>
            <div class="card-body">
                <div class="info-item"><span class="label">From:</span><span class="value">${item[fromField] || 'N/A'}</span></div>
                <div class="info-item"><span class="label">Created:</span><span class="value">${item[createdField] ? formatDate(item[createdField]) : 'N/A'}</span></div>
                <div class="info-item"><span class="label">Status:</span><span class="status-badge ${statusClass}">${item[statusField] || 'N/A'}</span></div>
                <div class="info-item"><span class="label">Replied:</span><span class="value">${item.replied_by || 'Not Replied'}</span></div> 
            </div>
        </div>
    `;
    
    $("#card-container").append(cardHtml);
    $(".card").last().on("click", function() {
        const $this = $(this);
        const cardId = $this.data("id");
        const cardType = $this.data("type");
        
        $('.card').removeClass('active');
        $this.addClass('active');
        
        localStorage.setItem('activeCardId', cardId);
        
        loadCardDetail(cardId, cardType);
    });
}
function loadCardDetail(cardId, cardType) {
    const panelBody = $("#right-panel .panel-body").addClass("empty")
        .html(`<i class="fa fa-spinner fa-spin" style="font-size: 48px;"></i><p>Memuat data...</p>`);

    setTimeout(() => {
        
        $.ajax({
            url: `/card-detail?id=${cardId}&type=${cardType}`,
            type: "GET",
            dataType: "json",
            success: function(response) {
                const commonFields = {
                    'Request Type': 'subject',
                    'From': 'from'
                };

                const typeSpecificFields = {
                    'ocean-freight': {
                        'Scope': 'scope', 'Term': 'term', 'POL': 'pol', 'POD': 'pod', 'Service': 'service',
                        'Customer Name': 'customer_name', 'Shipper Name': 'shipper_name',
                        'Container Type': 'container_type', 'Cargo Type': 'cargo_type', 'LCL': 'lcl',
                        'Container 20ft': 'container_20ft', 'Container 40ft': 'container_40ft',
                        'Container 40hq': 'container_40hq', 'Container 45ft': 'container_45ft','Notes': 'notes'
                    },
                    'forklift-rental': {
                        'Customer Name': 'customer_name', 'Customer Address': 'customer_address',
                        'Capacity': 'capacity', 'Target': 'target_rate', 'Notes': 'notes'
                    },
                    'rate-project': {
                        'Cargo Type': 'cargo_type', 'Container Vessel Type': 'container_vessel_type',
                        'Term': 'term', 'POL': 'pol', 'POD': 'pod', 'Customer Name': 'customer_name',
                        'Shipper Name': 'shipper_name', 'Commodity': 'commodity', 'HS Code': 'hs_code',
                        'Gross Weight': 'gross_weight', 'Dimension': 'dimension', 'Deadline': 'deadline',
                        'Target Rate': 'target_rate', 'Notes': 'notes'
                    },
                    'trucking-rate': {
                        'Pickup Address': 'pickup_address', 'Scope': 'scope', 'Customer Name': 'customer_name',
                        'Commodity': 'commodity', 'Delivery Address': 'delivery_address', 'Truck Type': 'truck_type',
                        'HS Code': 'hs_code', 'Gross Weight': 'gross_weight', 'Length': 'length',
                        'Width': 'width', 'Height': 'height', 'Target Rate': 'target_rate',
                        'Project Name': 'project_name', 'Temperature': 'temperature', 'Deadline': 'deadline',
                        'Notes': 'notes'
                    }
                };

                const fields = { ...commonFields, ...(typeSpecificFields[cardType] || {}) };
                
              
                let detailHtml = `
                    <div class="detail-container">
                        ${Object.entries(fields).map(([label, field]) => {
                            let fieldContent = response[field];

                             
                            if (label === 'Notes' && fieldContent) {
                                fieldContent = fieldContent.replace(/\n/g, '<br>');
                            }

                           
                            return fieldContent ? `<div class="detail-item"><strong>${label}:</strong> <span>${fieldContent}</span></div>` : '';
                        }).join('')}
                    </div>
                `;
                
                
                let repliesEndpoint;
                switch(cardType) {
                    case 'forklift-rental':
                        repliesEndpoint = `/get-replies/forklift?id=${cardId}`;
                        break;
                    case 'ocean-freight':
                        repliesEndpoint = `/get-replies/ocean?id=${cardId}`;
                        break;
                    case 'rate-project':
                        repliesEndpoint = `/get-replies/rate?id=${cardId}`;
                        break;
                    case 'trucking-rate':
                        repliesEndpoint = `/get-replies/trucking?id=${cardId}`;
                        break;
                    default:
                        repliesEndpoint = `/get-replies/${cardType}?id=${cardId}`;
                }
                
                $.ajax({
                    url: repliesEndpoint,
                    type: "GET",
                    dataType: "json",
                    success: function(repliesData) {
                        let repliesHtml = '';
        if (repliesData && repliesData.length > 0) {
            repliesHtml = `
                <div class="previous-replies mt-4 mb-4">
                   <hr>
                    <div class="replies-container">
                        ${repliesData.map(reply => {

                            let attachmentHtml = '';
                            if (reply.file_path) {
                                const fileExt = reply.file_path.split('.').pop().toLowerCase();
                                const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(fileExt);

                                if (isImage) {
                                    attachmentHtml = `
                                        <div class="attachment mt-3">
                                            <p><strong>Attachment:</strong></p>
                                            <div class="image-preview mb-2">
                                                <img src="/storage/${reply.file_path}" class="img-fluid" style="max-height: 300px; max-width: 100%;">
                                            </div>
                                           <a href="/storage/${reply.file_path}" download class="btn btn-sm btn-primary" style="font-size: 12px; padding: 5px 10px;"><i class="fa fa-download" style="font-size: 12px"></i> Download</a>
                                        </div>
                                    `;
                                } else {
                                     
                                    let fileIcon = 'fa-file';
                                    let fileType = 'File';

                               
                                    if (['pdf'].includes(fileExt)) {
                                        fileIcon = 'fa-file-pdf';
                                        fileType = 'PDF';
                                    } else if (['doc', 'docx'].includes(fileExt)) {
                                        fileIcon = 'fa-file-word';
                                        fileType = 'Document';
                                    } else if (['xls', 'xlsx'].includes(fileExt)) {
                                        fileIcon = 'fa-file-excel';
                                        fileType = 'Spreadsheet';
                                    }

                                    
                                    const fileName = reply.file_path.split('/').pop();

                                    attachmentHtml = `
                                        <div class="attachment mt-3">
                                            <p><strong>Attachment:</strong></p>
                                            <a href="/storage/${reply.file_path}" download class="btn btn-sm btn-primary">
                                                <i class="fa ${fileIcon}"style="font-size: 12px"></i> Download ${fileType}
                                            </a>
                                        </div>
                                    `;
                                }
                            }

                           
                            const formattedReplyText = reply.reply.replace(/\n/g, '<br>');  

                            return `
                                <div class="reply-item card mb-3">
                                 <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f4f4f4; border: 1px solid #ccc; border-radius: 5px 5px 0 0;">
  
                                        <span>${reply.reply_user}</span>
                                         <small>${new Date(reply.reply_at).toLocaleString('en-GB', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit',
                                    hour12: true
                                })}</small>
                                    </div>
                                    <div class="card-body" style="border: 1px solid #ccc; border-radius: 0px 0px 5px 5px;">
                                         
                                        <p>${formattedReplyText}</p>  
                                        ${attachmentHtml}
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
            `;
        }

       

                        const replyFormHtml = `
                            <div class="reply-form mt-4">
    <h4>Reply Request</h4>
    <form id="reply-form" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
        <div class="form-group">
            <textarea id="reply-text" name="reply" class="form-control" rows="5" placeholder="Write your reply here..."></textarea>
        </div>
        <div>
                        <label class="detail-label" for="file">Attachment</label>
                        <input type="file" class="form-control" id="file" name="file" 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        <small class="text-muted">Max file size: 2MB</small>
                    </div>
        <input type="hidden" name="request_id" value="${cardId}">
        <button type="submit" id="send-reply" class="btn btn-primary mt-3">Send Reply</button>
    </form>
</div>
                        `;

                      
                        panelBody.html(detailHtml + repliesHtml + replyFormHtml).removeClass("empty").fadeIn(400);

                        
                        $("#reply-form").on("submit", function(e) {
                            e.preventDefault();
                            const replyText = $("#reply-text").val().trim();
                            
                            if (!replyText) {
                                showSnackbar("Please enter a reply before sending", "error"); 
                                return;
                            }
                            
                            
                            const formData = new FormData(this);
                            
                         
                            let endpoint;
                            switch(cardType) {
                                case 'forklift-rental':
                                    endpoint = '/reply/forklift';
                                    break;
                                case 'ocean-freight':
                                    endpoint = '/reply/ocean-freight';
                                    break;
                                case 'rate-project':
                                    endpoint = '/reply/rate-project';
                                    break;
                                case 'trucking-rate':
                                    endpoint = '/reply/trucking-rate';
                                    break;
                                default:
                                    endpoint = '/submit-reply';
                            }

                            
                            $("#send-reply").prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');

$.ajax({
    url: endpoint,
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(response) {
        showSnackbar("Reply sent successfully!", "success");  

        
        $("#send-reply").prop('disabled', false).text('Send Reply');

        
        setTimeout(() => {
            loadCardDetail(cardId, cardType);
            loadData();   
        }, 1000);
    },
    error: function(xhr, status, error) {
        console.error("Error details:", {
            status: xhr.status,
            statusText: xhr.statusText,
            responseText: xhr.responseText
        });

        showSnackbar("Error sending reply!", "error"); 

     
        $("#send-reply").prop('disabled', false).text('Send Reply');
    }
});
                        });
                    },
                    error: function(xhr) {
                        console.error("Error loading replies:", xhr);
                        
                         
                        const replyFormHtml = `
                            <div class="reply-form mt-4">
                                <h4>Reply Request</h4>
                                <form id="reply-form" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                    <div class="form-group">
                                        <textarea id="reply-text" name="reply" class="form-control" rows="5" placeholder="Write your reply here..."></textarea>
                                    </div>
                                    <div>
                        <label class="detail-label" for="file">Attachment</label>
                        <input type="file" class="form-control" id="file" name="file" 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        <small class="text-muted">Max file size: 2MB</small>
                    </div>
                                    <input type="hidden" name="request_id" value="${cardId}">
                                    <button type="submit" id="send-reply" class="btn btn-primary mt-3">Send Reply</button>
                                </form>
                            </div>
                        `;
                        
                        panelBody.html(detailHtml + '<p class="text-danger">Failed to load previous replies.</p>' + replyFormHtml).removeClass("empty");
                    }
                });
            },
            error: xhr => {
                console.error("Error loading detail:", xhr);
                panelBody.html('<p>Error loading data, please try again.</p>');
            }
        });
    }, 500);  
}

function updateSortButtonState(sortOrder) {
    document.querySelectorAll('.sort-btn').forEach(btn => 
        btn.classList.toggle('active', btn.getAttribute('data-sort') === sortOrder)
    );
}

function formatDate(date) {
    const d = new Date(date);
    return `${String(d.getDate()).padStart(2, '0')} ${d.toLocaleString('default', { month: 'short' })} ${d.getFullYear()}, ${String(d.getHours()).padStart(2, '0')}:${String(d.getMinutes()).padStart(2, '0')}`;
}

function toggleFilterPanel() {
    const filterPanel = document.querySelector('.filter-panel');
    const filterOverlay = document.querySelector('.filter-overlay');
    
    filterPanel.classList.toggle('active');
    filterOverlay.classList.toggle('active');
    
    
    if (filterPanel.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

function clearFilters() {
    
    document.querySelectorAll('.filter-options input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    
    document.querySelector('input[name="status"][value="all"]').checked = true;
    
   
    applyFilters();
}

function applyFilters() {
    
    const currentSortOrder = $('.sort-btn.active').data('sort') || 'desc';
    
 
    toggleFilterPanel();
    
 
    loadData(currentSortOrder);
}



function getCurrentUser() {
    return currentUser;  
}

$(document).ready(function() {
    loadData('desc');
    
    $('.sort-btn').on('click', function(e) {
        e.preventDefault();
        loadData($(this).data('sort'));
        $('.dropdown-content').toggle();
    });

    $('.btn-type').on('click', function(e) {
        e.stopPropagation();
        $('.dropdown-content').toggle();
    });

    $(document).on('click', e => {
        if (!$(e.target).closest('.dropdown').length) $('.dropdown-content').hide();
        if (!$(e.target).closest('.dropdownfilter').length) $('.dropdownfilter-content').hide();
    });
});
 
 
    function showSelection() {
 
    document.getElementById('requestSelection').classList.remove('d-none');
    
   
    document.getElementById('formsContainer').classList.add('d-none');
    
 
    document.querySelectorAll('.request-form').forEach(form => {
        form.classList.add('d-none');
    });
}

 
function showForm(formId) {
 
    document.getElementById('requestSelection').classList.add('d-none');
    
 
    document.getElementById('formsContainer').classList.remove('d-none');
    
 
    document.querySelectorAll('.request-form').forEach(form => {
        form.classList.add('d-none');
    });
    
 
    document.getElementById(formId).classList.remove('d-none');
}

 
document.getElementById('containerType').addEventListener('change', function() {
    const containerDetails = document.getElementById('containerDetails');
    if (this.value) {
        containerDetails.classList.remove('d-none');
    } else {
        containerDetails.classList.add('d-none');
    }
});


function showSnackbar(message, type = "success") {
    let snackbar = document.getElementById("snackbar");
    let icon = "";

   
    if (type === "success") {
        icon = '<i class="fa fa-check-circle" style="color: #4CAF50;"></i>';
    } else if (type === "error") {
        icon = '<i class="fa fa-exclamation-circle" style="color: #F44336;"></i>';
    } else {
        icon = '<i class="fa fa-info-circle" style="color: #2196F3;"></i>';
    }

    snackbar.innerHTML = icon + message;
    snackbar.classList.add("show");

    
    setTimeout(() => {
        snackbar.classList.remove("show");
    }, 6000);
}
 