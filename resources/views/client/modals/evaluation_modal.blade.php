<div class="modal fade" id="evaluationModal">
   <div class="modal-dialog modal-lg" style="min-width: 50%;">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Evaluation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <div class="col-md-12">
                  <div class="tabs-section">
                     <ul class="nav nav-pills" id="evaluation-tabs">
                        <li class="nav-item border rounded border-success" onclick="loadEmployeedataInput()">
                           <a class="nav-link active" href="#tab-2" data-bs-toggle="tab">Data Inputs</a>
                        </li>
                        {{-- <li class="nav-item border rounded border-success"><a href="#tab-3" class="nav-link" data-bs-toggle="tab">Performance Appraisal</a></li> --}}
                        <li class="nav-item border rounded border-success"><a href="#tab-4" class="nav-link" data-bs-toggle="tab">Evaluation Files</a></li>
                        <li class="nav-item border rounded border-success"><a href="
                          @if(Auth::user()->department_id == 9)/kpi_stats/it/index
                          @elseif(Auth::user()->department_id == 2)/kpi_stats/sales/index
                          @elseif(Auth::user()->department_id == 3)/kpi_stats/engineering/index
                          @elseif(Auth::user()->department_id == 4)/kpi_stats/customer_service/index
                          @elseif(Auth::user()->department_id == 5)/kpi_stats/qa/index
                          @elseif(Auth::user()->department_id == 6)/kpi_stats/hr/index
                          @elseif(Auth::user()->department_id == 7)/kpi_stats/plant_services/index
                          @elseif(Auth::user()->department_id == 8)/kpi_stats/production/index
                          @elseif(Auth::user()->department_id == 10)/kpi_stats/material_management/index
                          @elseif(Auth::user()->department_id == 12)/kpi_stats/management/index
                          @elseif(Auth::user()->department_id == 14)/kpi_stats/assembly/index
                          @elseif(Auth::user()->department_id == 15)/kpi_stats/fabrication/index
                          @elseif(Auth::user()->department_id == 13)/kpi_stats/marketing/index
                          @elseif(Auth::user()->department_id == 16)/kpi_stats/traffic_and_distribution/index
                          @elseif(Auth::user()->department_id == 17)/kpi_stats/painting/index
                          @elseif(Auth::user()->department_id == 19)/kpi_stats/filunited/index
                          @elseif(Auth::user()->department_id == 120)/kpi_stats/production_planning/index
                          @elseif(Auth::user()->department_id == 1)/kpi_stats/accounting/index
                          @endif
                          " class="nav-link">KPI Result Overview</a>
                        </li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane in active" id="tab-2">
                            <div class="row pt-3">
                                 <div class="col-sm-2">
                                    <button type="button" class="btn btn-primary btn-sm" id="datainputmodal" onclick="createFunction()"><i class="fa fa-plus"></i> Create</button>
                                 </div>
                                 <div class="col-sm-10 py-2" align="center">
                                    <label style="width: 10%;">Year</label>
                                    <select style="width: 15%;" class="year filters" name="yearfilter" id="yearfilter" onchange="loadEmployeedataInput()">
                                       <option value="2018" {{ date('y') == 18 ? 'selected' : '' }}>2018</option>
                                       <option value="2019" {{ date('y') == 19 ? 'selected' : '' }}>2019</option>
                                       <option value="2020" {{ date('y') == 20 ? 'selected' : '' }}>2020</option>
                                       <option value="2021" {{ date('y') == 21 ? 'selected' : '' }}>2021</option>
                                       <option value="2022" {{ date('y') == 22 ? 'selected' : '' }}>2022</option>
                                       <option value="2023" {{ date('y') == 23 ? 'selected' : '' }}>2023</option>
                                       <option value="2024" {{ date('y') == 24 ? 'selected' : '' }}>2024</option>
                                       <option value="2025" {{ date('y') == 25 ? 'selected' : '' }}>2025</option>
                                       <option value="2026" {{ date('y') == 26 ? 'selected' : '' }}>2026</option>
                                       <option value="2027" {{ date('y') == 27 ? 'selected' : '' }}>2027</option>
                                       <option value="2028" {{ date('y') == 28 ? 'selected' : '' }}>2028</option>
                                       <option value="2029" {{ date('y') == 29 ? 'selected' : '' }}>2029</option>
                                       <option value="2030" {{ date('y') == 30 ? 'selected' : '' }}>2030</option>
                                    </select>
                                    <label style="width: 8%;">Month</label>
                                    <select style="width: 20%;" class="month filters" name="monthfilter" id="monthfilter" onchange="loadEmployeedataInput()">
                                       <option value="01" {{ date('m') == 1 ? 'selected' : '' }}>January</option>
                                       <option value="02" {{ date('m') == 2 ? 'selected' : '' }}>February</option>
                                       <option value="03" {{ date('m') == 3 ? 'selected' : '' }}>March</option>
                                       <option value="04" {{ date('m') == 4 ? 'selected' : '' }}>April</option>
                                       <option value="05" {{ date('m') == 5 ? 'selected' : '' }}>May</option>
                                       <option value="06" {{ date('m') == 6 ? 'selected' : '' }}>June</option>
                                       <option value="07" {{ date('m') == 7 ? 'selected' : '' }}>July</option>
                                       <option value="08" {{ date('m') == 8 ? 'selected' : '' }}>August</option>
                                       <option value="09" {{ date('m') == 9 ? 'selected' : '' }}>September</option>
                                       <option value="10" {{ date('m') == 10 ? 'selected' : '' }}>October</option>
                                       <option value="11" {{ date('m') == 11 ? 'selected' : '' }}>November</option>
                                       <option value="12" {{ date('m') == 12 ? 'selected' : '' }}>December</option>
                                    </select>
                                    <label style="width: 20%;">Evalution Period</label>
                                    <select style="width: 15%;" name="schedentry" id="schedentry" onchange="loadEmployeedataInput()">
                                       <option value="Monthly">Monthly</option>
                                       <option value="Quarterly">Quarterly</option>
                                       <option value="Semi-Annual">Semi-Annual</option>
                                       <option value="Annual">Annual</option>
                                    </select>
                                 </div>
                                 <div id='tblDatainput' style="padding-top: 30px;"></div>
                              </div>                                          
                           </div>
                           <div class="tab-pane" id="tab-3">
                              <div class="row">
                                 <div class="col-sm-12">
                                    {{-- <div id="appraisal-table"></div> --}}
                                 </div>
                              </div>
                           </div>
                           <div class="tab-pane" id="tab-4">
                              <div class="row">
                                 <div class="col-md-12" style="margin: 7px;">
                                    <form></form>
                                    <div class="col-md-12">
                                       @if(in_array($designation, ['Human Resources Head', 'Director of Operations', 'President']))
                                          <button type="button" class="btn btn-primary" id="add-evaluation-file-btn"><i class="fa fa-plus"></i> Evaluation</button>
                                       @endif
                                       </div>
                                       <div class="col-md-12">
                                       <div id="evaluation-table"></div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>