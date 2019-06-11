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



  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="../../index.html"><img src="../../images/logo.svg" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="../../index.html"><img src="../../images/logo-mini.svg" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <div class="search-field ml-4 d-none d-md-block">
          <form class="d-flex align-items-stretch h-100" action="#">
            <div class="input-group">
              <input type="text" class="form-control bg-transparent border-0" placeholder="Search">
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
                    
                    <div class="col-lg-12 grid-margin stretch-card">
                                    
                        <div class="card">
                          <div class="card-body">
                            
                            
                                <h1 class="card-title"><?php echo "Search For $keyword";?></h1>
                            
                                                                
                            <div class="widget-content">

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

            

            $num_results =$result['response']['numFound'];
            $totalpage=(integer)(($num_results+9)/10);
            $page=1;

            //分割div

            for ($i=1;$i<=$num_results;$i++) {
                if($i%10==1){echo"<div id=\"$page\"style=\"display:none\">";}
                $paper=$result['response']['docs'][$i-1] ;
                $paper_id = $paper['PaperID'];
                $papername2 = ucwords($paper['PaperName']);
                echo "<a href=\"../papers/paper_info.php?paper_id=$paper_id\"><h3>$papername2</h3></a>";
                echo "<table>";
                echo "<tr><td width = '120'><b> Authors: </b></td><td>";
                foreach ($paper['AuthorName'] as $idx => $author) {
                    $author_id = substr($paper['AuthorID'][$idx],2,-3);
                    $author2 = ucwords($author);
                    echo "<a href=\".\..\authors\author_info.php?page=1&author_id=$author_id\">$author2</a>";
                    echo "; ";
                }
                echo "</td></tr>";
                echo "<tr><td><b> Conference: </b></td><td>";
                $conference_id =$paper['ConferenceID'];
                $conference = $paper['ConferenceName'];
                echo "<a href=\".\..\conference\conference_info.php?page=1&conference_id=$conference_id\">$conference</a>";
                echo "; ";
                echo "</td></tr>";
                echo "</table>";
                echo "<hr>";

                if($i%10==0&&$i>1){echo"Page: ";echo$page;echo"<br>";echo"  Total of Pages: ";echo$totalpage;echo"<br>";echo"Total of Papers: ";echo$num_results;$page+=1;echo"</div>";}
                else if ($i==$num_results){echo"Page: ";echo$page;echo"<br>";echo"  Total of Pages: ";echo$totalpage;echo"<br>";echo"Total of Papers: ";echo$num_results;echo"</div>";}

            }   
//翻页
           echo'
<script type="text/javascript">
var now=1; 
var totalpage ='; echo $totalpage ;echo';
 document.getElementById("1").style.display="";
</script>';
           echo'
<script type="text/javascript">
 $(document).ready(function(){
$("#but1").click(function(){
    document.getElementById(now.toString()).style.display="none";
    document.getElementById("1").style.display="";
    now=1;
    for (var it=1;it<=totalpage;++it){
        var itit="b"+it.toString();
        if(it>5)document.getElementById(itit).style.display="none";
        else document.getElementById(itit).style.display="";
        
    } 
      });});
</script>';
            
            echo'
<script type="text/javascript">
 $(document).ready(function(){
$("#but2").click(function(){
    if(now<totalpage){
    document.getElementById(now.toString()).style.display="none";
    document.getElementById((now+1).toString()).style.display="";
    now+=1;
       }
    else document.getElementById((totalpage).toString()).style.display="";
       for (var it=1;it<=totalpage;++it){
        var itit="b"+it.toString();
        if (document.getElementById(itit)){
        if(it<(now-2)||it>(now+2)) { document.getElementById(itit).style.display="none"; }
        else { document.getElementById(itit).style.display=""; }}
        
    }if(totalpage>=1&&totalpage<=5)document.getElementById("b1").style.display=""; 
    else if(totalpage>=2&&totalpage<=5)document.getElementById("b2").style.display="";else if(totalpage>=3&&totalpage<=5)document.getElementById("b3").style.display="";else if(totalpage>=4&&totalpage<=5)document.getElementById("b4").style.display="";else if(
    totalpage==5)document.getElementById("b5").style.display="" ;else
    
     if(now>=1&&now<=2){document.getElementById("b4").style.display="";
     document.getElementById("b5").style.display="";}
     if(now>totalpage-2)document.getElementById("b"+(totalpage-4).toString()).style.display="";   
     if(now>totalpage-1)document.getElementById("b"+(totalpage-3).toString()).style.display="";
      });});  
</script>';

 echo'
<script type="text/javascript">
 $(document).ready(function(){
$("#but3").click(function(){
   if(now>1){
    document.getElementById(now.toString()).style.display="none";
    document.getElementById((now-1).toString()).style.display="";
    now-=1;}else  document.getElementById("1").style.display=""; 

    for (var it=1;it<=totalpage;++it){
        var itit="b"+it.toString();
        if(it<(now-2)||it>(now+2))document.getElementById(itit).style.display="none";
        else document.getElementById(itit).style.display="";
        
    }if(totalpage>=1&&totalpage<=5)document.getElementById("b1").style.display=""; 
    else if(totalpage>=2&&totalpage<=5)document.getElementById("b2").style.display="";else if(totalpage>=3&&totalpage<=5)document.getElementById("b3").style.display="";else if(totalpage>=4&&totalpage<=5)document.getElementById("b4").style.display="";else if(
    totalpage==5)document.getElementById("b5").style.display="" ;else
    if(now>=1&&now<=2){document.getElementById("b4").style.display="";
     document.getElementById("b5").style.display="";}
     if(now>totalpage-2)document.getElementById("b"+(totalpage-4).toString()).style.display="";   
     if(now>totalpage-1)document.getElementById("b"+(totalpage-3).toString()).style.display="";  
        });});
</script>';

echo'
<script type="text/javascript">
 $(document).ready(function(){
$("#but4").click(function(){
    document.getElementById(now.toString()).style.display="none";
    document.getElementById(totalpage.toString()).style.display="";
    now=totalpage;

    for (var it=1;it<=totalpage;++it){
        var itit=\'b\'+it.toString();
        if(it<totalpage-4)document.getElementById(itit).style.display="none";
        else document.getElementById(itit).style.display="";}
        });});
</script>';
echo"<div>";
echo"<style type=\"text/css\">

.button {
    
    display: inline-block;
}</style>";
echo"<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">
<div class=\"button\"><button type=\"button\" class=\"btn btn-outline-secondary \"id=\"but1\">First</button></div>

<div class=\"button\"><button type=\"button\" class=\"btn btn-outline-secondary \"id=\"but3\">Previous</button></div>";

for($j=1;$j<=$totalpage;++$j){
    $jj=(string)$j;
    echo"<div class =\"button\"><button type=\"button\" class=\"btn btn-outline-secondary \"id=\"b$jj\">$jj</button></div>";}
  
echo"<div class=\"button\"><button type=\"button\" class=\"btn btn-outline-secondary \"id=\"but2\">Next</button></div>
<div class=\"button\"><button type=\"button\" class=\"btn btn-outline-secondary \"id=\"but4\">Last</button></div></div>";
echo"
<script  type=\"text/javascript\">
 var oBtn=document.getElementsByClassName(\"button\");
 for (var it=6;it<=totalpage;++it){
        var itit='b'+it.toString();
        document.getElementById(itit).style.display=\"none\";
        
        
    }

for(var t=1;t<=totalpage;++t){
    oBtn[t+1].index=t;
    oBtn[t+1].onclick=function(){
       var pppp=(this.index).toString();
       document.getElementById(now.toString()).style.display=\"none\";
       document.getElementById(pppp).style.display=\"\"; 
       now=this.index;
       for (var it=1;it<=totalpage;++it){
        var itit='b'+it.toString();
        if(it<(now-2)||it>(now+2))document.getElementById(itit).style.display=\"none\";
        else document.getElementById(itit).style.display=\"\";
        
    }if(totalpage>=1&&totalpage<=5)document.getElementById('b1').style.display=\"\"; 
    else if(totalpage>=2&&totalpage<=5)document.getElementById('b2').style.display=\"\";else if(totalpage>=3&&totalpage<=5)document.getElementById('b3').style.display=\"\";else if(totalpage>=4&&totalpage<=5)document.getElementById('b4').style.display=\"\";else if(
    totalpage==5)document.getElementById('b5').style.display=\"\" ;else
    if(now>=1&&now<=2){document.getElementById('b4').style.display=\"\";
     document.getElementById('b5').style.display=\"\";}
     if(now>totalpage-2)document.getElementById('b'+(totalpage-4).toString()).style.display=\"\";   
     if(now>totalpage-1)document.getElementById('b'+(totalpage-3).toString()).style.display=\"\";  
    }}
 
</script>";
            
        }

else {echo "No Search Results!";}

?>
                      </div>

                    </div> <!-- /widget-content -->
                    
                </div> <!-- /widget -->
 
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
</body>

</html>
