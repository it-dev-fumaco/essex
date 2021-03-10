<div class="modal fade" id="evaluationModal">
   <div class="modal-dialog modal-lg" style="width: 50%;">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Evaluation</h4>
         </div>
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <div class="col-md-12">
                  <div class="tabs-section">
                     <ul class="nav nav-tabs">
                        {{-- <li><a href="#tab-1" data-toggle="tab">KPI Results</a></li> --}}
                        <li class="active" onclick="loadEmployeedataInput()"><a href="#tab-2" data-toggle="tab">Data Inputs</a></li>
                        <li><a href="#tab-3" data-toggle="tab">Performance Appraisal</a></li>
                        <li><a href="#tab-4" data-toggle="tab">Evaluation Files</a></li>
                        <li><a href="
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
                          ">KPI Result Overview</a></li>
                        {{-- <li><a href="#tab-5" data-toggle="tab">KPI Schedule</a></li> --}}
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane" id="tab-1">
                           <div class="row">
                              <div class="col-sm-12">
                                 <div class="col-sm-12" align="center">
                                    <label style="width: 10%;">Year</label>
                                    <select style="width: 20%;" class="year filters" name="yearfilterresult" id="yearfilterresult" onchange="loadKpiResult()">
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
                                    <select style="width: 20%;" class="month filters" name="monthfilterresult" id="monthfilterresult" onchange="loadKpiResult()">
                                       <option value="1" {{ date('m') == 1 ? 'selected' : '' }}>January</option>
                                       <option value="2" {{ date('m') == 2 ? 'selected' : '' }}>February</option>
                                       <option value="3" {{ date('m') == 3 ? 'selected' : '' }}>March</option>
                                       <option value="4" {{ date('m') == 4 ? 'selected' : '' }}>April</option>
                                       <option value="5" {{ date('m') == 5 ? 'selected' : '' }}>May</option>
                                       <option value="6" {{ date('m') == 6 ? 'selected' : '' }}>June</option>
                                       <option value="7" {{ date('m') == 7 ? 'selected' : '' }}>July</option>
                                       <option value="8" {{ date('m') == 8 ? 'selected' : '' }}>August</option>
                                       <option value="9" {{ date('m') == 9 ? 'selected' : '' }}>September</option>
                                       <option value="10" {{ date('m') == 10 ? 'selected' : '' }}>October</option>
                                       <option value="11" {{ date('m') == 11 ? 'selected' : '' }}>November</option>
                                       <option value="12" {{ date('m') == 12 ? 'selected' : '' }}>December</option>
                                    </select>
                                 </div>
                                 <div id="kpi-result-table"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane in active" id="tab-2">
                            <div class="row">
                                  <!-- <div class="col-sm-2" style="float: right;padding-right: 25%;">
                                     <a href="/evaluation/kpi_result/overview"><button type="button" class="btn btn-success" id="overview_view"><i class="fa fa-search"></i> Overview</button>
                                      </a> 
                                  </div> -->
                                  <div class="col-sm-2">
                                      <button type="button" class="btn btn-primary" id="datainputmodal" onclick="createFunction()"><i class="fa fa-plus"></i> Create</button>
                                  </div>

                                 <div class="col-sm-10" align="center" style="padding-top: 20px;">
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
                                 <br>
                                
                                 <div id='tblDatainput' style="padding-top: 60px;"></div>
                            </div>                                          
                        </div>
                        <div class="tab-pane" id="tab-3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="appraisal-table"></div>
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
                        {{-- <div class="tab-pane" id="tab-5">
                           <div class="row">
                              <div class="col-md-12" id="emp-kpi-sched">
                                     Error
                              </div>
                           </div>
                        </div> --}}
                    </div>
                </div>
               </div>
            </div>
         </div>
         </form>
      </div>
   </div>
</div>

