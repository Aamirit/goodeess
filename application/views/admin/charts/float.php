<div class="content-wrapper" style="min-height: 1345.6px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Flot Charts</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Flot</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- interactive chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Interactive Area Chart
                </h3>

                <div class="card-tools">
                  Real time
                  <div class="btn-group" id="realtime" data-toggle="btn-toggle">
                    <button type="button" class="btn btn-default btn-sm active" data-toggle="on">On</button>
                    <button type="button" class="btn btn-default btn-sm" data-toggle="off">Off</button>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div id="interactive" style="height: 300px; padding: 0px; position: relative;"><canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 1028px; height: 300px;" width="1028" height="300"></canvas><canvas class="flot-overlay" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 1028px; height: 300px;" width="1028" height="300"></canvas><div class="flot-svg" style="position: absolute; top: 0px; left: 0px; height: 100%; width: 100%; pointer-events: none;"><svg style="width: 100%; height: 100%;"><g class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; inset: 0px;"><text style="position: absolute; text-align: center;" x="28.02500009536743" y="294" class="flot-tick-label tickLabel">0</text><text style="position: absolute; text-align: center;" x="123.84797998871466" y="294" class="flot-tick-label tickLabel">10</text><text style="position: absolute; text-align: center;" x="223.64595978669445" y="294" class="flot-tick-label tickLabel">20</text><text style="position: absolute; text-align: center;" x="323.44393958467424" y="294" class="flot-tick-label tickLabel">30</text><text style="position: absolute; text-align: center;" x="423.24191938265403" y="294" class="flot-tick-label tickLabel">40</text><text style="position: absolute; text-align: center;" x="523.0398991806338" y="294" class="flot-tick-label tickLabel">50</text><text style="position: absolute; text-align: center;" x="622.8378789786136" y="294" class="flot-tick-label tickLabel">60</text><text style="position: absolute; text-align: center;" x="722.6358587765934" y="294" class="flot-tick-label tickLabel">70</text><text style="position: absolute; text-align: center;" x="822.4338385745732" y="294" class="flot-tick-label tickLabel">80</text><text style="position: absolute; text-align: center;" x="922.231818372553" y="294" class="flot-tick-label tickLabel">90</text></g><g class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; inset: 0px;"><text style="position: absolute; text-align: right;" x="8.949999809265137" y="269" class="flot-tick-label tickLabel">0</text><text style="position: absolute; text-align: right;" x="1" y="15" class="flot-tick-label tickLabel">70</text><text style="position: absolute; text-align: right;" x="1" y="232.71428571428572" class="flot-tick-label tickLabel">10</text><text style="position: absolute; text-align: right;" x="1" y="196.42857142857142" class="flot-tick-label tickLabel">20</text><text style="position: absolute; text-align: right;" x="1" y="160.14285714285714" class="flot-tick-label tickLabel">30</text><text style="position: absolute; text-align: right;" x="1" y="123.85714285714286" class="flot-tick-label tickLabel">40</text><text style="position: absolute; text-align: right;" x="1" y="87.57142857142857" class="flot-tick-label tickLabel">50</text><text style="position: absolute; text-align: right;" x="1" y="51.285714285714285" class="flot-tick-label tickLabel">60</text></g></svg></div></div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
          <div class="col-md-6">
            <!-- Line chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Line Chart
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div id="line-chart" style="height: 300px; padding: 0px; position: relative;"><canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 486.5px; height: 300px;" width="486" height="300"></canvas><canvas class="flot-overlay" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 486.5px; height: 300px;" width="486" height="300"></canvas><div class="flot-svg" style="position: absolute; top: 0px; left: 0px; height: 100%; width: 100%; pointer-events: none;"><svg style="width: 100%; height: 100%;"><g class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; inset: 0px;"><text style="position: absolute; text-align: center;" x="37.02500009536743" y="294" class="flot-tick-label tickLabel">0</text><text style="position: absolute; text-align: center;" x="101.83981491018224" y="294" class="flot-tick-label tickLabel">2</text><text style="position: absolute; text-align: center;" x="166.65462972499705" y="294" class="flot-tick-label tickLabel">4</text><text style="position: absolute; text-align: center;" x="231.46944453981186" y="294" class="flot-tick-label tickLabel">6</text><text style="position: absolute; text-align: center;" x="296.28425935462667" y="294" class="flot-tick-label tickLabel">8</text><text style="position: absolute; text-align: center;" x="357.1240742648089" y="294" class="flot-tick-label tickLabel">10</text><text style="position: absolute; text-align: center;" x="421.9388890796237" y="294" class="flot-tick-label tickLabel">12</text></g><g class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; inset: 0px;"><text style="position: absolute; text-align: right;" x="1" y="269" class="flot-tick-label tickLabel">-1.5</text><text style="position: absolute; text-align: right;" x="1" y="226.66666666666669" class="flot-tick-label tickLabel">-1.0</text><text style="position: absolute; text-align: right;" x="1" y="184.33333333333334" class="flot-tick-label tickLabel">-0.5</text><text style="position: absolute; text-align: right;" x="5.983333587646484" y="15" class="flot-tick-label tickLabel">1.5</text><text style="position: absolute; text-align: right;" x="5.983333587646484" y="142" class="flot-tick-label tickLabel">0.0</text><text style="position: absolute; text-align: right;" x="5.983333587646484" y="99.66666666666667" class="flot-tick-label tickLabel">0.5</text><text style="position: absolute; text-align: right;" x="5.983333587646484" y="57.333333333333336" class="flot-tick-label tickLabel">1.0</text></g></svg></div></div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->

            <!-- Area chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Area Chart
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div id="area-chart" style="height: 338px; padding: 0px; position: relative;" class="full-width-chart"><canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 524.5px; height: 338px;" width="524" height="338"></canvas><canvas class="flot-overlay" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 524.5px; height: 338px;" width="524" height="338"></canvas></div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col -->

          <div class="col-md-6">
            <!-- Bar chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Bar Chart
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div id="bar-chart" style="height: 300px; padding: 0px; position: relative;"><canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 486.5px; height: 300px;" width="486" height="300"></canvas><canvas class="flot-overlay" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 486.5px; height: 300px;" width="486" height="300"></canvas><div class="flot-svg" style="position: absolute; top: 0px; left: 0px; height: 100%; width: 100%; pointer-events: none;"><svg style="width: 100%; height: 100%;"><g class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; inset: 0px;"><text style="position: absolute; text-align: center;" x="98.14999961853027" y="294" class="flot-tick-label tickLabel">February</text><text style="position: absolute; text-align: center;" x="184.70000076293945" y="294" class="flot-tick-label tickLabel">March</text><text style="position: absolute; text-align: center;" x="266.67500019073486" y="294" class="flot-tick-label tickLabel">April</text><text style="position: absolute; text-align: center;" x="345.6666669845581" y="294" class="flot-tick-label tickLabel">May</text><text style="position: absolute; text-align: center;" x="23.90833282470703" y="294" class="flot-tick-label tickLabel">January</text><text style="position: absolute; text-align: center;" x="419.71666717529297" y="294" class="flot-tick-label tickLabel">June</text></g><g class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; inset: 0px;"><text style="position: absolute; text-align: right;" x="8.949999809265137" y="269" class="flot-tick-label tickLabel">0</text><text style="position: absolute; text-align: right;" x="8.949999809265137" y="205.5" class="flot-tick-label tickLabel">5</text><text style="position: absolute; text-align: right;" x="1" y="15" class="flot-tick-label tickLabel">20</text><text style="position: absolute; text-align: right;" x="1" y="142" class="flot-tick-label tickLabel">10</text><text style="position: absolute; text-align: right;" x="1" y="78.5" class="flot-tick-label tickLabel">15</text></g></svg></div></div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->

            <!-- Donut chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Donut Chart
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div id="donut-chart" style="height: 300px; padding: 0px; position: relative;"><canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 486.5px; height: 300px;" width="486" height="300"></canvas><canvas class="flot-overlay" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 486.5px; height: 300px;" width="486" height="300"></canvas><span class="pieLabel" id="pieLabel0" style="position: absolute; top: 69.5px; left: 301.2px;"><div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">Series2<br>30%</div></span><span class="pieLabel" id="pieLabel1" style="position: absolute; top: 209.5px; left: 279.2px;"><div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">Series3<br>20%</div></span><span class="pieLabel" id="pieLabel2" style="position: absolute; top: 128.5px; left: 120.2px;"><div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">Series4<br>50%</div></span></div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>