<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Search Information</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../../node_modules/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../node_modules/perfect-scrollbar/dist/css/perfect-scrollbar.min.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../images/favicon.png" />

</head>
<body>

<script TYPE="TEXT/JAVASCRIPT" src="http://localhost/js/jquery-1.7.2.min.js"></script>
<?php
$keyword = $_GET["keyword"];
$link = mysqli_connect("localhost:3306", 'root', '', 'FINAL');
?>

    <?php 
        $keyword = $_GET["keyword"];
        if ($keyword) {
            $ch = curl_init();
            $timeout = 5;
            $query = urlencode(str_replace(' ', '+', $keyword));
            $url = "http://localhost:8983/solr/FINAL/select?q=keyword%3A".$query."&start=0&rows=100000&wt=json";

            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $result = json_decode(curl_exec($ch), true);
            curl_close($ch);
        }
        //收集echarts 统计数据
        //chart1: years, count_year
        //chart2: conferences, count_conference
        $years_data = array();

        $years0 = array();
        $conferences_data = array();
        $all_paper = $result["response"]["docs"];
        //var_dump($all_paper);
        $authors_data = array();
        foreach ($all_paper as $paper_data) {
            if(array_key_exists(intval($paper_data["Year"]), $years_data)){
                $years_data[intval($paper_data["Year"])]++;
            }else{
                $years_data[intval($paper_data["Year"])]=1;

            }
            if(array_key_exists($paper_data["ConferenceName"], $conferences_data)){
                $conferences_data[$paper_data["ConferenceName"]]++;
            }else{
                $conferences_data[$paper_data["ConferenceName"]]=1;
            }

            if(!in_array(intval($paper_data["Year"]), $years0)){
                $years0[] = intval($paper_data["Year"]);
            }
            foreach ($paper_data["AuthorName"] as $authorName) {
                if(array_key_exists($authorName, $authors_data)){
                    $authors_data[$authorName]++;
                }else{
                    $authors_data[$authorName] = 1;
                }
            }
        }
        for($i=min($years0);$i<=max($years0);$i++){
            if(!array_key_exists($i, $years_data)){
                $years_data[$i] = 0;
            }
        }

        ksort($years_data);
        $years = array();
        $count_year = array();
        foreach ($years_data as $key => $value) {

            array_push($years,intval($key));
            array_push($count_year,$value);
        }

        ksort($conferences_data);
        $conferences = array();
        $count_conference = array();
        foreach ($conferences_data as $key => $value) {
            array_push($conferences,$key);
            array_push($count_conference,$value);
        }

        arsort($authors_data);
        $all_authors = array_keys($authors_data);
        $authors = array();
        $author_num = array();
        for ($i=0; $i < min(10,sizeof($all_authors)); $i++) { 
            array_push($authors,$all_authors[$i]);
            array_push($author_num,$authors_data[$all_authors[$i]]);
        }

        $years = json_encode($years);
        $count_year = json_encode($count_year);
        $conferences = json_encode($conferences);
        $count_conference = json_encode($count_conference);
        $authors = json_encode($authors);
        $author_num = json_encode($author_num);

    ?>
    


  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="../../index.html"><img src="../../images/logo.svg" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="../../index.html"><img src="../../images/logo-mini.svg" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <div class="search-field ml-4 d-none d-md-block">
          <form class="d-flex align-items-stretch h-100" action="../../search_result.php">
            <div class="input-group">
              <input type="text" name="keyword" class="form-control bg-transparent border-0" placeholder="Search">
              </div>
              <div class="input-group-addon bg-transparent border-0 search-button">
                <button type="submit" class="btn btn-sm bg-transparent px-0">
                  <i class="mdi mdi-magnify"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper" style="margin-top: 59px">
      <div class="row row-offcanvas row-offcanvas-right">
        <!-- partial:../../partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <?php echo "<a class=\"nav-link\" href=\"./search_info.php?keyword=$keyword\">" ?>
                <span class="menu-title">Results</span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <?php echo "<a class=\"nav-link\" href=\"./search_charts.php?keyword=$keyword\">" ?>
                <span class="menu-title">Charts</span>
                <i class="mdi mdi-chart-bar menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <?php echo "<a class=\"nav-link\" href=\"../../index.php\">" ?>
                <span class="menu-title">Back To Home</span>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
              </a>
            </li>
          </ul>
          <div class="sidebar-progress">
            <p>Total Paper</p>
            <div class="progress progress-sm">
              <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p>9999 Papers Included</p>
          </div>
        </nav>
        <!-- partial -->
        <div class="content-wrapper">




                <!-- 放置paper相关信息 -->
                <div class="row">
                    <div class="col-lg-6 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Year chart</h4>
                          <div id="year_chart" style="float: left;width: 400px;height:325px;"></div>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Conference chart</h4>
                        <div id="conference_chart" style="float: left;width: 400px;height:325px;"></div>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Author chart</h4>
                        <div id="author_chart" style="float: left;width: 400px;height:325px;"></div>
                        </div>
                      </div>
                    </div>
                </div>


                
                

                


























        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Powered by ..... </span>
           
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- row-offcanvas ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->



  <!-- plugins:js -->
  <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
  <script src="../../node_modules/popper.js/dist/umd/popper.min.js"></script>
  <script src="../../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../../node_modules/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.min.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/misc.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <!-- End custom js for this page-->
    <script src="../../js/echarts.js"></script>


        <script type="text/javascript">
            var myChart = echarts.init(document.getElementById('year_chart'));
            var years1 = eval(decodeURIComponent('<?php echo urlencode($years);?>'));
            var count_year1 = eval(decodeURIComponent('<?php echo urlencode($count_year);?>'));
     
            option = {
                title: {
                    text: 'Publish Year'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data:['number of papers']
                },
                xAxis: {
                    type: 'category',
                    data: years1
                },
                yAxis: {
                    type: 'value',
                    minInterval: 1
                },
                series: [
                {
                    name:'papers',
                    type: 'line',
                    data: count_year1
                },
                ]
            };
            
            myChart.setOption(option);
        </script>

        <!--echarts Conference Source -->
        <script type="text/javascript">
            var myChart = echarts.init(document.getElementById('conference_chart'));
            var conferences1 = eval(decodeURIComponent('<?php echo urlencode($conferences);?>'));
            var count_conference1 = eval(decodeURIComponent('<?php echo urlencode($count_conference);?>'));
     
            option = {
                title: {
                    text: 'Conference'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data:['number of papers']
                },
                xAxis: {
                    type: 'category',
                    data: conferences1,
                    axisLabel: {
                        interval:0,
                        rotate: 40
                    }
                },
                yAxis: {
                    type: 'value'
                },
                series: [
                {
                    name:'papers',
                    type: 'bar',
                    data: count_conference1
                },
                ]
            };
            
            myChart.setOption(option);
        </script>


        <!-- author charts authors author_num-->
        <script type="text/javascript">
            var myChart = echarts.init(document.getElementById('author_chart'));
            var authors1 = eval(decodeURIComponent('<?php echo urlencode($authors);?>'));
            var author_num1 = eval(decodeURIComponent('<?php echo urlencode($author_num);?>'));

            for(i=0;i<authors1.length;i++){
                authors1[i] = authors1[i].replace(/[+]/g," ");
            }
     
            option = {
                title: {
                    text: 'Top Authors'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data:['number of papers']
                },
                xAxis: {
                    type: 'category',
                    data: authors1,
                    axisLabel: {
                        interval: 0,
                        rotate: 40,
                        textStyle: {
                            fontSize: 9
                        }
                    }
                },
                yAxis: {
                    type: 'value',
                    minInterval: 1
                },
                series: [
                {
                    name:'papers',
                    type: 'bar',
                    data: author_num1
                },
                ]
            };
            
            myChart.setOption(option);
        </script>
</body>

</html>
