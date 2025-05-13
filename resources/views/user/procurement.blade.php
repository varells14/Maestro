@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="{{ asset('css/procurement.css') }}" rel="stylesheet">
        <script src="{{ asset('js/procurement.js') }}" defer></script>
    </head>

<body>
    <div class="headertitle">
        <div class="headerleft">
            <div class="textheader">PROCUREMENT REQUEST</div>
            <div class="dropdown">
                <button class="btn-type"><i class="fa-solid fa-arrow-down-wide-short"></i></button>
                <div class="dropdown-content">
                    <a href="#" class="sort-btn active" data-sort="desc">Latest request</a>
                    <a href="#" class="sort-btn" data-sort="asc">Oldest request</a>
                </div>
            </div>
         
        </div>
        <div class="headerright">
            <button class="btn-submit"  data-bs-toggle="modal" data-bs-target="#requestModal"><i class="fa-regular fa-file-plus"></i> New Request</button>
            
            <!-- Filter Container replaces dropdownfilter -->
            <div class="filter-container">
                <button class="btn-filter" onclick="toggleFilterPanel()">
                  REQUEST FILTER
                </button>
                
                <div class="filter-panel">
                    <div class="filter-header">
                        <h3>Filters</h3>
                        <button class="close-filter" onclick="toggleFilterPanel()">×</button>
                    </div>
                    
                    <div class="filter-section">
                        <h4>REQUEST TYPE</h4>
                        <div class="filter-options">
                            <label><input type="checkbox" name="forklift" /> Forklift Rental</label>
                            <label><input type="checkbox" name="oceanfreight" /> Ocean Freight</label>
                            <label><input type="checkbox" name="trucking" /> Trucking Rate</label>
                            <label><input type="checkbox" name="projectrate" /> Project Rate</label>
                        </div>
                    </div>
                    
                    <div class="filter-section">
                        <h4>REQUEST STATUS</h4>
                        <div class="filter-options">
                            <label><input type="radio" name="status" value="all" checked /> All</label>
                            <label><input type="radio" name="status" value="open" /> Open</label>
                            <label><input type="radio" name="status" value="closed" /> Closed</label>
                        </div>
                    </div>
                    
                    <div class="filter-section">
                        <h4>PARTICIPATION</h4>
                        <div class="filter-options">
                            <label><input type="checkbox" name="requestByMe" /> Request by me</label>
                            <label><input type="checkbox" name="repliedByMe" /> Replied by me</label>
                        </div>
                    </div>
                    
                    <div class="filter-actions">
                        <button class="btn-apply-filter" onclick="applyFilters()">Apply Filters</button>
                        <button class="btn-clear-filter" onclick="clearFilters()">Clear All</button>
                    </div>
                </div>
                
                <div class="filter-overlay" onclick="toggleFilterPanel()"></div>
            </div>
        </div>
    </div>

    <div class="container">
    <div class="leftpanel" id="card-container"></div>
    <div class="rightpanel" id="right-panel">
        <div class="headerside">
            REQUEST DETAIL
            <label class="switch">
                <input type="checkbox" id="toggle" onclick="togglePanel()">
                <span class="slider round"></span>
            </label>
        </div>
        <div class="panel-body empty" id="panel-body">
            <i class="fa-regular fa-file"></i>
            <p>Select an item to view</p>
        </div>
    </div>
</div>




    <div id="snackbar"></div>





    
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestModalLabel">New Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Selection Screen -->
            <div id="requestSelection" class="modal-body p-4">
                <div class="request-type-grid row row-cols-1 row-cols-md-4 g-4">
                    <!-- Forklift Request -->
                    <div class="col">
                        <div class="request-type-card" onclick="showForm('forkliftForm')">
                            <i class="fa-regular fa-forklift" style="font-size: 24px;"></i>
                            <h6>Forklift Request</h6>
                        </div>
                    </div>
                    

                    <!-- Ocean Freight -->
                    <div class="col">
                        <div class="request-type-card" onclick="showForm('oceanForm')">
                            <i class="fa-regular fa-ship" style="font-size: 24px;"></i>
                            <h6>Ocean Freight</h6>
                        </div>
                    </div>

                    <!-- Project/Spot Rate -->
                    <div class="col">
                        <div class="request-type-card" onclick="showForm('projectForm')">
                            <i class="fa-solid fa-ticket" style="font-size: 24px;"></i>
                            <h6>Project/Spot Rate</h6>
                        </div>
                    </div>

                    <!-- Trucking Rate -->
                    <div class="col">
                        <div class="request-type-card" onclick="showForm('truckingForm')">
                            <i class="fa-regular fa-truck" style="font-size: 24px;"></i>
                            <h6>Trucking Rate</h6>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Forms Container -->
            <div id="formsContainer" class="d-none">
                <!-- Back to Selection Button -->
               
                    <button type="button" class="btn btn-link text-decoration-none" onclick="showSelection()">
                    <i class="fa-solid fa-arrow-left"></i> Back to Selection
                    </button>
                 
                
                <!-- Ocean Freight Form -->
                <form id="oceanForm" class="request-form d-none p-4" action="{{ route('storeOcean') }}" method="POST">
                    @csrf
                    <h5 class="mb-4">Ocean Freight Request</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="oceanScope" name="ocean_scope" placeholder="Scope" required>
                                <label for="oceanScope">Scope</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="oceanTerm" name="ocean_term" placeholder="Term" required>
                                <label for="oceanTerm">Term</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="oceanPol" name="ocean_pol" placeholder="POL" required>
                                <label for="oceanPol">Port of Loading (POL)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="oceanPod" name="ocean_pod" placeholder="POD" required>
                                <label for="oceanPod">Port of Discharge (POD)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="oceanService" name="ocean_service" placeholder="Service" required>
                                <label for="oceanService">Service</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="oceanCustomerName" name="ocean_customer_name" placeholder="Customer Name" required>
                                <label for="oceanCustomerName">Customer Name</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="oceanShipperName" name="ocean_shipper_name" placeholder="Shipper Name" required>
                                <label for="oceanShipperName">Shipper Name</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="cargoType" name="ocean_cargo_type" placeholder="Cargo Type" required>
                                <label for="cargoType">Cargo Type</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="containerType" name="ocean_container_type">
                                    <option value="" selected>Select Container Type</option>
                                    <option value="DRY">Dry</option>
                                    <option value="REEFER">Reefer</option>
                                    <option value="GOH">GOH</option>
                                </select>
                                <label for="containerType">Container Type</label>
                            </div>
                        </div>
                        
                        <!-- Container Details (hidden by default) -->
                        <div id="containerDetails" class="col-12 d-none">
                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label for="ocean20ft" class="form-label">20ft</label>
                                    <input type="number" class="form-control" id="ocean20ft" name="ocean_20ft" min="0" step="1">
                                </div>
                                <div class="col-md-3">
                                    <label for="ocean40ft" class="form-label">40ft</label>
                                    <input type="number" class="form-control" id="ocean40ft" name="ocean_40ft" min="0" step="1">
                                </div>
                                <div class="col-md-3">
                                    <label for="ocean40hq" class="form-label">40HQ</label>
                                    <input type="number" class="form-control" id="ocean40hq" name="ocean_40hq" min="0" step="1">
                                </div>
                                <div class="col-md-3">
                                    <label for="ocean45ft" class="form-label">45ft</label>
                                    <input type="number" class="form-control" id="ocean45ft" name="ocean_45ft" min="0" step="1">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="oceanNotes" name="ocean_notes" placeholder="Notes" style="height: 100px"></textarea>
                                <label for="oceanNotes">Notes</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">Submit Request</button>
                    </div>
                </form>

                <form id="forkliftForm" class="request-form d-none p-4" action="{{ route('storeForklift') }}" method="POST">
                    @csrf
                    <h5 class="mb-4">Forklift Rental Request</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="forkliftCustomerName" name="forklift_customer_name" placeholder="Customer Name" required>
                                        <label for="forkliftCustomerName">Customer Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                            <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="forkliftCustomerAddress" name="forklift_customer_address" placeholder="Customer Address" required>
                                        <label for="forkliftCustomerAddress">Customer Address</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="forkliftCapacity" name="forklift_capacity" placeholder="Capacity" required>
                                        <label for="forkliftCapacity">Capacity (tons)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="forkliftTargetRate" name="forklift_target_rate" placeholder="Target Rate" required>
                                        <label for="forkliftTargetRate">Target Rate</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="forkliftNotes" name="forklift_notes" placeholder="Notes" style="height: 100px"></textarea>
                                        <label for="forkliftNotes">Notes</label>
                                    </div>
                                </div>
                            </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">Submit Request</button>
                    </div>
                </form>
                <form id="projectForm" class="request-form d-none p-4" action="{{ route('storeRateProject') }}" method="POST">
                    @csrf
                    <h5 class="mb-4">Project/Spot Rate Request</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="rateContainerVesselType" name="rate_container_vessel_type" placeholder="Container/Vessel Type" required>
                                        <label for="rateContainerVesselType">Container/Vessel Type</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="rateTerm" name="rate_term" placeholder="Term" required>
                                        <label for="rateTerm">Term</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="ratePol" name="rate_pol" placeholder="POL" required>
                                        <label for="ratePol">POL</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="ratePod" name="rate_pod" placeholder="POD" required>
                                        <label for="ratePod">POD</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="rateCustomerName" name="rate_customer_name" placeholder="Customer Name" required>
                                        <label for="rateCustomerName">Customer Name</label>
                                    </div>
                                </div>

                           
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="rateShipperName" name="rate_shipper_name" placeholder="Shipper Name" required>
                                        <label for="rateShipperName">Shipper Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="rateCargoType" name="rate_cargo_type" placeholder="Cargo Type" required>
                                        <label for="rateCargoType">Cargo Type</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="rateCommodity" name="rate_commodity" placeholder="Commodity" required>
                                        <label for="rateCommodity">Commodity</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="rateHsCode" name="rate_hs_code" placeholder="HS Code" required>
                                        <label for="rateHsCode">HS Code</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="rateGrossWeight" name="rate_gross_weight" placeholder="Gross Weight" required>
                                        <label for="rateGrossWeight">Gross Weight</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="rateDimension" name="rate_dimension" placeholder="Dimension" required>
                                        <label for="rateDimension">Dimension</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="rateDeadline" name="rate_deadline" required>
                                        <label for="rateDeadline">Deadline</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="rateTargetRate" name="rate_target_rate" placeholder="Target Rate" required>
                                        <label for="rateTargetRate">Target Rate</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="rateNotes" name="rate_notes" placeholder="Notes" style="height: 100px"></textarea>
                                        <label for="rateNotes">Notes</label>
                                    </div>
                                </div>
                            </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">Submit Request</button>
                    </div>
                </form>
                <form id="truckingForm" class="request-form d-none p-4" action="{{ route('storeTrucking') }}" method="POST">
                    @csrf
                    <h5 class="mb-4">Trucking Rate Request</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="truckingScope" name="trucking_scope" placeholder="Scope" required>
                                        <label for="truckingScope">Scope</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="truckingCustomerName" name="trucking_customer_name" placeholder="Customer Name" required>
                                        <label for="truckingCustomerName">Customer Name</label>
                                    </div>
                                </div>
                               
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="truckingCommodity" name="trucking_commodity" placeholder="Commodity" required>
                                        <label for="truckingCommodity">Commodity</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="truckingPickupAddress" name="trucking_pickup_address" placeholder="Pick Up Address" required>
                                        <label for="truckingPickupAddress">Pick Up Address</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="truckingDeliveryAddress" name="trucking_delivery_address" placeholder="Delivery Address" required>
                                        <label for="truckingDeliveryAddress">Delivery Address</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="truckingTruckType" name="trucking_truck_type" placeholder="Truck Type">
                                        <label for="truckingTruckType">Truck Type</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="truckingHsCode" name="trucking_hs_code" placeholder="HS Code">
                                        <label for="truckingHsCode">HS Code</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="truckingGrossWeight" name="trucking_gross_weight" placeholder="Gross Weight" required>
                                        <label for="truckingGrossWeight">Gross Weight (KG)</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="truckingLength" name="trucking_length" placeholder="Length" required>
                                        <label for="truckingLength">Length (CM)</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="truckingWidth" name="trucking_width" placeholder="Width" required>
                                        <label for="truckingWidth">Width (CM)</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="truckingHeight" name="trucking_height" placeholder="Height" required>
                                        <label for="truckingHeight">Height (CM)</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="truckingTargetRate" name="trucking_target_rate" placeholder="Target Rate">
                                        <label for="truckingTargetRate">Target Rate</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="truckingProjectName" name="trucking_project_name" placeholder="Project Name">
                                        <label for="truckingProjectName">Project Name</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="truckingTemperature" name="trucking_temperature" placeholder="Temperature">
                                        <label for="truckingTemperature">Temperature (C)</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                <div class="form-floating">
                                        <input type="date" class="form-control" id="truckingDeadline" name="trucking_deadline" required>
                                        <label for="truckingDeadline">Deadline</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="truckingNotes" name="trucking_notes" placeholder="Notes" style="height: 100px"></textarea>
                                        <label for="truckingNotes">Notes</label>
                                    </div>
                                </div>
                            </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">Submit Request</button>
                    </div>
                </form>
                
                
       
            </div>

       
            
        </div>
    </div>
</div>
    



</body>
</html>



@endsection