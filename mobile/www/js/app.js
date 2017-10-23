checkcookies();
function checkcookies() {
if(localStorage.email){
gethome("2017-04-20");
}else{
getlogin();
}
}//checkcookies

function getlogin() {
$$("#content").html("");
$$(".navbar").css('display', 'none');
$$("#contenthead").css('display', 'none');
$$(".page-content").addClass("login-screen-content");
var content='\
<!-- Should be a direct child of BODY -->\
          <div class="row">\
          <div class="col-25"></div>\
          <div class="col-50">\
          <img src="img/logo.png" width="60%" height="60%">\
          </div>\
          <div class="col-25"></div>\
          </div>\
            <!-- Login form -->\
            <form>\
              <div class="list-block">\
                <ul>\
                  <li class="item-content">\
                  <div class="item-media"><i class="material-icons">mail</i></div>\
                    <div class="item-inner">\
                      <div class="item-input">\
                        <input type="text" id="email" name="email" placeholder="Email">\
                      </div>\
                    </div>\
                  </li>\
                  <li class="item-content">\
                  <div class="item-media"><i class="material-icons">https</i></div>\
                    <div class="item-inner">\
                      <div class="item-input">\
                        <input type="password" id="password" name="password" placeholder="Password">\
                      </div>\
                    </div>\
                  </li>\
                </ul>\
              </div>\
              <div class="row">\
              <div class="col-25"></div>\
                <div class="col-50">\
                  <a id="login" href="#" class="button  button-fill">LOGIN</a>\
                </div>\
                <div class="col-25"></div>\
                </div>\
                <div class="row">\
                <div class="col-25"></div>\
                <div class="col-50">\
                  <a id="forget" href="#" class="button  button-fill">FORGET PASSWORD</a>\
                </div>\
                <div class="col-25"></div>\
              </div> \
            </form>\
';
$$("#content").append(content);
}//getlogin()

$$(document).on("click", "#login", function() {
  var email = $$('#email').val();
  var password = $$('#password').val();
var url = "http://"+hosturl+"/api/getlogin.php";
$$.getJSON( url, {
    email:email,
    password:password}
,function( data ) {
$$.each(data, function(i, field){
var status  =field.status;

if (status=="no") {
myApp.alert("Wong Password !", 'APK MASTER');
}else{
localStorage.email=email;
localStorage.fullname=field.fullname;
gethome("2017-04-20");
}


});

});

});//click login

function gethome(datenow) {
$$("#content").html("");
$$("#right_index").html("");
$$(".navbar").css('display', 'block');
$$("#contenthead").css('display', 'none');
$$(".page-content").removeClass("login-screen-content");
var calendar='\
<i id="calendar" class="f7-icons" report="realtime">calendar</i>\
';
$$("#right_index").append(calendar);
//var datereport="2017-04-20";
//var datereport = new Date("2017-04-20");
var formattedDate = new Date(datenow);
var d = formattedDate.getDate();
var m =  formattedDate.getMonth();
m += 1;  // JavaScript months are 0-11
var y = formattedDate.getFullYear();
var datereport=y + "-" + m + "-" + d;
var datereportshow=d + "/" + m + "/" + y;
getRealtimeTransaction(datereport,datereportshow);
}//gethome

function getRealtimeTransaction(datereport,datereportshow) {
var content= '\
<div class="card">\
    <div class="card-header">รายงานประจำวันที่ '+datereportshow+'</div>\
    <div class="card-content">\
        <div class="card-content-inner"><canvas id="myChart" width="600" height="400"></canvas></div>\
    </div>\
    <div class="card-footer">\
    <div class="list-block">\
      <ul>\
        <li class="item-content">\
          <div class="item-inner">\
            <div class="item-title">wood:ปริมาณไม้เข้า(ตัน)</div>\
          </div>\
        </li>\
        <li class="item-content">\
          <div class="item-inner">\
            <div class="item-title">price:ราคาเฉลี่ยต่อกิโลกรัม(บาท)</div\
          </div>\
        </li>\
        <li class="item-content">\
          <div class="item-inner">\
            <div class="item-title">car:จำนวนรถ(คัน)</div>\
          </div>\
        </li>\
      </ul>\
    </div>\
    </div>\
</div> \
';
$$("#content").append(content);
garphrealtime(datereport);
}//RealtimeTransaction

function garphrealtime(datereport) {
  var url = "http://"+hosturl+"/api/reportrealtime.php";
var email =localStorage.email;
var backgroundColor = [
	'rgba(255, 99, 132,0.2)',
	'rgba(255, 159, 64,0.2)',
	'rgba(255, 205, 86,0.2)',
	'rgba(75, 192, 192,0.2)',
	'rgba(54, 162, 235,0.2)',
	'rgba(153, 102, 255,0.2)',
	'rgba(201, 203, 207,0.2)'
];
var borderColor=
[
  'rgba(255, 99, 132,1)',
	'rgba(255, 159, 64,1)',
	'rgba(255, 205, 86,1)',
	'rgba(75, 192, 192,1)',
	'rgba(54, 162, 235,1)',
	'rgba(153, 102, 255,1)',
	'rgba(201, 203, 207,1)'
];
var label=["Wood", "Price", "Car"];
var datasets=[];
  $$.getJSON( url, {
      email:email,
      datereport:datereport
    }
  ,function( data ) {
    //  console.log(data);
  $$.each(data, function(i, field){
    //console.log(i);
if (field[0].transaction_count>0) {
  var a={
              label: field[0].shortname,
              data: [field[0].weight_total,field[0].price_total,field[0].transaction_count],
              backgroundColor:backgroundColor[i],
              borderColor:borderColor[i],
              borderWidth: 1
          };
  datasets.push(a);
  var content='\
  <div class="card">\
      <div class="card-content">\
      <div class="list-block">\
        <ul>\
          <li class="item-content">\
            <div class="item-inner">\
              <div id="detailrealtime" datereport="'+datereport+'" sawId="'+field[0].sawId+'" class="item-title"><a href="#detail_realtime">รายละเอียดของสาขา '+field[0].shortname+'</a></div>\
            </div>\
          </li>\
          </ul>\
          </div>\
          </div>\
          </div>\
  ';
  $$("#content").append(content);
}
});
graprealtime(label,datasets);
});

}

function graprealtime(label,datasets) {
  var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: label,
        datasets:datasets
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
Chart.plugins.register({
    afterDatasetsDraw: function(chart, easing) {
        // To only draw at the end of animation, check for easing === 1
        var ctx = chart.ctx;

        chart.data.datasets.forEach(function (dataset, i) {
            var meta = chart.getDatasetMeta(i);
            if (!meta.hidden) {
                meta.data.forEach(function(element, index) {
                    // Draw the text in black, with the specified font
                    ctx.fillStyle = 'rgb(0, 0, 0)';

                    var fontSize = 10;
                    var fontStyle = 'normal';
                    var fontFamily = 'Helvetica Neue';
                    ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                    // Just naively convert to string for now
                    var dataString = dataset.data[index].toString();

                    // Make sure alignment settings are correct
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    var padding = 5;
                    var position = element.tooltipPosition();
                    ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                });
            }
        });
    }
});
}



$$(document).on("click", "#calendar", function() {
$$("#contenthead").html("");
$$("#contenthead").css('display', 'block');
var report=$$(this).attr("report");
var proname=$$(this).attr("proname");
var content='\
<div class="list-block">\
  <ul>\
    <li>\
      <div class="item-content">\
     <input type="text" placeholder="เลือกวันที่" readonly id="calendar-default" ><i id="search" class="f7-icons" report="'+report+'" proname="'+proname+'">search</i>\
      </div>\
    </li>\
  </ul>\
</div>\
';
$$("#contenthead").append(content);
var calendarDefault = myApp.calendar({
    input: '#calendar-default',
});
});


$$(document).on("click", "#search", function() {
var dateselect=$$("#calendar-default").val();
var report=$$(this).attr("report");
var proname=$$(this).attr("proname");
switch (report) {
  case 'realtime':
    gethome(dateselect);
    break;
    case 'month_report':
    //gethome(dateselect);
    alert("test");
    getDataPro_name(dateselect,proname);
    break;
  default:

}

});

$$(document).on("click", "#detailrealtime", function() {
var datereport=$$(this).attr("datereport");
var sawId=$$(this).attr("sawId");
getdetailrealtime(sawId,datereport);
});

function getdetailrealtime(sawId,datereport) {
  $$("#content_detail").html('');
  //mainView.router.load({pageName: 'detailrealtime'});
  var content='';
  var url = "http://"+hosturl+"/api/reportrealtimedetail.php";
  content+='\
  <div class="card">\
      <div class="card-content">\
      <div class="list-block media-list">\
        <ul>';
  $$.getJSON( url, {
      sawId:sawId,
      datereport:datereport
    }
  ,function( data ) {
  console.log(data[0].length);
  //var dataarray=data[0];
  $$.each(data[0], function(i, field){
    //console.log(i);
    //console.log(field);

    content+='\
    <li>\
         <a id="listdetail" href="#" class="item-link item-content"\
          weight_no="'+field.weight_no+'"\
          sawId="'+field.sawId+'"\
          car_register="'+field.car_register+'"\
          cus_name="'+field.cus_name+'"\
          pro_name="'+field.pro_name+'"\
          place_name="'+field.place_name+'"\
          datetime_in="'+field.datetime_in+'"\
          datetime_out="'+field.datetime_out+'"\
          weight_net="'+field.weight_net+'"\
          price="'+field.price+'"\
          bill_no="'+field.bill_no+'"\
         >\
           <div class="item-inner">\
             <div class="item-title-row">\
               <div class="item-title">'+field.cus_name+'</div>\
               <div class="item-after">'+field.detailtime+'</div>\
             </div>\
             <div class="item-subtitle">'+field.pro_name+'</div>\
             <div class="item-text"><p>น้ำหนัก '+field.weight_net+' ตัน<p></div>\
           </div>\
         </a>\
       </li>\
    ';

  });//each
  content+='\
  </ul>\
  </div>\
  </div>\
  </div>\
  ';
  $$("#content_detail").append(content);
  //console.log(content);
});//getjson


}//function detailtime



$$(document).on('page:init', '.page[data-page="detail_realtime_id"]', function (e) {
  // Do something here when page with data-page="about" attribute loaded and initialized
  var mySwiper = myApp.swiper('.swiper-container', {
    pagination:'.swiper-pagination'
  });

});

$$(document).on('page:reinit', '.page[data-page="detail_realtime_id"]', function (e) {
  // Do something here when page with data-page="about" attribute loaded and initialized
  var mySwiper = myApp.swiper('.swiper-container', {
    pagination:'.swiper-pagination'
  });

});


$$(document).on("click", "#listdetail", function() {

var sawId=$$(this).attr("sawId");
var weight_no=$$(this).attr("weight_no");
var car_register=$$(this).attr("car_register");
var cus_name=$$(this).attr("cus_name");
var pro_name=$$(this).attr("pro_name");
var place_name=$$(this).attr("place_name");
var datetime_in=$$(this).attr("datetime_in");
var datetime_out=$$(this).attr("datetime_out");
var weight_net=$$(this).attr("weight_net");
var price=$$(this).attr("price");
var bill_no=$$(this).attr("bill_no");

$$("#center_detail_id").html('');
$$("#content_detail_id").html('');
$$("#center_detail_id").append(cus_name);

var content='';
content+='\
<div class="card">\
    <div class="card-header">bill no: '+bill_no+'</div>\
    <div class="card-content">\
<!-- Slider -->\
<div class="swiper-container">\
<div class="swiper-wrapper">\
';
var url = "http://"+hosturl+"/api/report_realtime_detail_id.php";
$$.getJSON( url, {
    sawId:sawId,
    weight_no:weight_no
  }
,function( data ) {
console.log(data.length);
//var dataarray=data[0];
$$.each(data[0], function(i, field){

content+='\
<div class="swiper-slide"><span><img src="http://afm.revocloudserver.com/uploadimage/'+field.file_image+'" width="100%" height="300"></span></div>\
';
});//each
content+='\
</div>\
<div class="swiper-pagination"></div>\
</div>\
';
content+='\
        <div class="card-content-inner">\
        <p>pro name: '+pro_name+'</p>\
        <p>weight_no: '+weight_no+'</p>\
        <p>car register: '+car_register+'</p>\
        <p>weight net: '+weight_net+' ตัน</p>\
        <p>price: '+price+' บาท</p>\
        <p>place name: '+place_name+'</p>\
        <p>datetime in: '+datetime_in+'</p>\
        <p>datetime out: '+datetime_out+'</p>\
        </div>\
    </div>\
</div> \
';
$$("#content_detail_id").append(content);
//console.log(content);
mainView.router.load({pageName: 'detail_realtime_id'});
});//getjson



});




$$(document).on("click", "#month_report", function() {
  $$("#content").html("");
  $$("#right_index").html("");
  $$(".navbar").css('display', 'block');
  $$("#contenthead").css('display', 'none');
  $$(".page-content").removeClass("login-screen-content");
  var proname=$$(this).attr("proname");
  var calendar='\
  <i id="calendar" class="f7-icons" report="month_report" proname="'+proname+'">calendar</i>\
  ';
  $$("#right_index").append(calendar);
var content='\
<div class="toolbar-inner">\
<a href="#tab1" class="tab-link active">\
<i class="icon demo-icon-1"></i>fgfg\
</a>\
<a href="#tab2" class="tab-link">\
<i class="icon demo-icon-2"></i>fgfg\
</a>\
<a href="#tab3" class="tab-link">\
<i class="icon demo-icon-3"></i>fgfgf\
</a>\
<a href="#tab4" class="tab-link">\
<i class="icon demo-icon-4"></i>fgfg\
</a>\
</div>\
';
$$("#tab").append(content);

  dateselect="2017-04-01";

getDataPro_name(dateselect,proname);

});
function getDataPro_name(datenow,proname) {
  var formattedDate = new Date(datenow);
  var d = formattedDate.getDate();
  var m =  formattedDate.getMonth();
  m += 1;  // JavaScript months are 0-11
  var y = formattedDate.getFullYear();
  var datereport=y + "-" + m + "-" + d;
  var datereportshow=d + "/" + m + "/" + y;
  var email =localStorage.email;
  var url = "http://"+hosturl+"/api/report_data_pro_name.php";
  $$.getJSON( url, {
      saw_id:'4',
      datereport:datereport,
      pro_name:proname
    }
  ,function( data ) {
  console.log(data);
  $$.each(data, function(i, field){
});//each
});//getJSON

}
