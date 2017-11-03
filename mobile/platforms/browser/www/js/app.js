checkcookies();
function checkcookies() {
if(localStorage.email){
  var formattedDate = new Date();
  var d = formattedDate.getDate();
  var m =  formattedDate.getMonth();
  m += 1;  // JavaScript months are 0-11
  var y = formattedDate.getFullYear();
  var datereport=y + "-" + m + "-" + d;
gethome(datereport);
}else{
getlogin();
}
}//checkcookies

var ptrContent = $$('.pull-to-refresh-content');
ptrContent.on('ptr:refresh', function (e) {
    // Emulate 2s loading
myApp.showPreloader('refresh...<br><span class="preloader-inner-half-circle"></span>');
setTimeout(function () {
      $$("#content").html("");
      var formattedDate = new Date();
      var d = formattedDate.getDate();
      var m =  formattedDate.getMonth();
      m += 1;  // JavaScript months are 0-11
      var y = formattedDate.getFullYear();
      var datereport=y + "-" + m + "-" + d;
    gethome(datereport);
      myApp.hidePreloader();
myApp.pullToRefreshDone();
  }, 2000);
  });

function getlogin() {
$$("#content").html("");
$$(".navbar").css('display', 'none');
$$("#tab").css('display', 'none');
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
                  <div class="item-media"><i class="f7-icons">mail</i></div>\
                    <div class="item-inner">\
                      <div class="item-input">\
                        <input type="text" id="email" name="email" placeholder="Email">\
                      </div>\
                    </div>\
                  </li>\
                  <li class="item-content">\
                  <div class="item-media"><i class="f7-icons">https</i></div>\
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
            </form>\
';
$$("#content").append(content);
}//getlogin()
$$(document).on("click", "#home", function() {
  var formattedDate = new Date();
  var d = formattedDate.getDate();
  var m =  formattedDate.getMonth();
  m += 1;  // JavaScript months are 0-11
  var y = formattedDate.getFullYear();
  var datereport=y + "-" + m + "-" + d;
gethome(datereport);
});



$$(document).on("click", "#login", function() {
  var email = $$('#email').val();
  var password = $$('#password').val();
if(email==""||password==""){
myApp.alert("No empty !", 'APK MASTER');
}
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
var formattedDate = new Date();
var d = formattedDate.getDate();
var m =  formattedDate.getMonth();
m += 1;  // JavaScript months are 0-11
var y = formattedDate.getFullYear();
var datereport=y + "-" + m + "-" + d;
var datereportshow=d + "/" + m + "/" + y;
gethome(datereport);
}


});

});

});//click login

$$(document).on("click", "#logout", function() {
myApp.closePanel();
myApp.alert("ออกจากระบบ", 'APK MASTER');
localStorage.removeItem("email");
localStorage.removeItem("fullname");
getlogin();
});//click logout
function gethome(datenow) {
$$("#content").html("");
$$("#fullname").html("");
$$(".navbar").css('display', 'block');
$$("#contenthead").css('display', 'none');
$$("#tab").css('display', 'none');
$$(".page-content").removeClass("login-screen-content");
$$("#right_index").html("");
var calendar='\
<i id="calendars" style="font-size: 30px;" class="f7-icons" report="realtime" >calendar</i>\
';
$$("#right_index").append(calendar);
var fullname='<i class="f7-icons">person</i>  '+localStorage.fullname;
$$("#fullname").append(fullname);
//var datereport="2017-04-20";
//var datereport = new Date("2017-04-20");
/*
var formattedDate = new Date(datenow);
var d = formattedDate.getDate();
var m =  formattedDate.getMonth();
m += 1;  // JavaScript months are 0-11
var y = formattedDate.getFullYear();
*/
var datereport=datenow;
var array_day = datereport.split("-");
var datereportshow=array_day[2] + "/" + array_day[1] + "/" + array_day[0];
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
            <div class="item-title">A:ปริมาณไม้เข้า(ตัน)</div>\
          </div>\
        </li>\
        <li class="item-content">\
          <div class="item-inner">\
            <div class="item-title">B:ราคาเฉลี่ยต่อตัน(บาท)</div\
          </div>\
        </li>\
        <li class="item-content">\
          <div class="item-inner">\
            <div class="item-title">C:จำนวนรถ(คัน)</div>\
          </div>\
        </li>\
        <li class="item-content">\
          <div class="item-inner">\
            <div class="item-title">D:ปริมาณไม้ท่อน(ตัน)</div>\
          </div>\
        </li>\
        <li class="item-content">\
          <div class="item-inner">\
            <div class="item-title">E:ปริมาณไม้ฟืน(ตัน)</div>\
          </div>\
        </li>\
        <li class="item-content">\
          <div class="item-inner">\
            <div class="item-title">F:ปริมาณไม้เกรด(ตัน)</div>\
          </div>\
        </li>\
        <li class="item-content">\
          <div class="item-inner">\
            <div class="item-title">G:ปริมาณปีกไม้(ตัน)</div>\
          </div>\
        </li>\
        <li class="item-content">\
          <div class="item-inner">\
            <div class="item-title">H:ปริมาณขี้เลื่อย(ตัน)</div>\
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
var label=["A","B","C","D","E","F","G","H"];
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
  var weight_total = Number(field[0].weight_total).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
  var price_total_per_kg =Number(field[0].price_total_per_kg).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
  var woodPices = Number(field[0].woodPices).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
  var fireWood = Number(field[0].fireWood).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
  var woodGade = Number(field[0].woodGade).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
  var woodWing = Number(field[0].woodWing).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
  var sawDust = Number(field[0].sawDust).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
  var a={
              label: field[0].shortname,
              stack: 'Stack 0',
              data: [weight_total,price_total_per_kg,field[0].transaction_count,woodPices,fireWood,woodGade,woodWing,sawDust],
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
          <li class="item-content" id="detailrealtime" datereport="'+datereport+'" sawId="'+field[0].sawId+'">\
            <div class="item-inner">\
              <div  class="item-title">รายละเอียดของสาขา '+field[0].shortname+'</div>\
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
      tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    responsive: true,
      scales: {
        xAxes: [{
                                   stacked: true,
                               }],
                               yAxes: [{
                                   stacked: true
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



$$(document).on("click", "#calendars", function() {
$$("#contenthead").html("");
$$("#contenthead").css('display', 'block');
var report=$$(this).attr("report");
var proname=$$(this).attr("proname");
var saw_id=$$(this).attr("saw_id");
if(report=="realtime"){

var content='\
<div class="list-block">\
  <ul>\
    <li>\
      <div class="item-content">\
     <input type="text" placeholder="เลือกวันที่" readonly id="calendar-default" ><i id="search" class="f7-icons" report="'+report+'">search</i>\
      </div>\
    </li>\
  </ul>\
</div>\
';
$$("#contenthead").append(content);
var calendarDefault = myApp.calendar({
    input: '#calendar-default',
});
}else {
  var content='\
  <div class="list-block">\
    <ul>\
      <li>\
        <div class="item-content">\
              <input type="text" placeholder="เลือกเดือน" readonly id="picker-input-month"><i id="search" class="f7-icons" report="'+report+'" proname="'+proname+'" saw_id="'+saw_id+'">search</i>\
        </div>\
      </li>\
    </ul>\
  </div> \
  ';
  $$("#contenthead").append(content);
  var formattedDate = new Date();
  var y = formattedDate.getFullYear();
  var array_year=[];
  for (var i = 0; i < 5; i++) {
    array_year[i]=y-i;
  }
  var array_month=[
    'มกราคม',
    'กุมภาพันธ์',
    'มีนาคม',
    'เมษายน',
    'พฤษภาคม',
    'มิถุนายน',
    'กรกฎาคม',
    'สิงหาคม',
    'กันยายน',
    'ตุลาคม',
    'พฤศจิกายน',
    'ธันวาคม',
  ];
  var myPicker = myApp.picker({
      input: '#picker-input-month',
      cols: [
         {
           values:array_month
         },
         {
           values:array_year
         }
       ]
  }
  );
}


/*
var myPicker2 = myApp.picker({
    input: '#picker-input-month',
    cols: [
       {
         values:array_month
       }
     ]
}
);
*/
});
$$(document).on("change", "#calendar-default", function(){
  search();
});
/*
$$(document).on("change", "#picker-input-month", function(){

search();
});
*/

function search() {
  var dateselect=$$("#calendar-default").val();
  var report=$$("#search").attr("report");
  var proname=$$("#search").attr("proname");
  var saw_id=$$("#search").attr("saw_id");
  switch (report) {
    case 'realtime':
      gethome(dateselect);
      break;
      case 'month_report':
      var dateselectmonth=$$("#picker-input-month").val();

      dateselectmonth=dateselectmonth.split(' ');
      var monthraw=dateselectmonth[0];
      var y=dateselectmonth[1];
      var month=getMonthraw(monthraw)+1;
      var dateselectnew=y+'-'+month+'-01';
      //console.log(month);
      //alert(month);
      //gethome(dateselect);
      getDataPro_name(dateselectnew,proname,saw_id);
      break;
      case 'profit_report':
      var dateselectmonth=$$("#picker-input-month").val();
      dateselectmonth=dateselectmonth.split(' ');
      var monthraw=dateselectmonth[0];
      var y=dateselectmonth[1];
      var month=getMonthraw(monthraw)+1;
      var dateselectnew=y+'-'+month+'-01';
      getDataProFit(dateselectnew,saw_id);
        break;
        case 'performance_report':
        var dateselectmonth=$$("#picker-input-month").val();
        dateselectmonth=dateselectmonth.split(' ');
        var monthraw=dateselectmonth[0];
        var y=dateselectmonth[1];
        var month=getMonthraw(monthraw)+1;
        var dateselectnew=y+'-'+month+'-01';
        getDataPerformance(dateselectnew,saw_id);
          break;
    default:

  }
}
$$(document).on("click", "#search", function() {
  search();
});



$$(document).on("click", "#detailrealtime", function() {


  /*
  $$("#right_index").css('display', 'none');
    $$("#content").css('display', 'none');
  $$("#left_index").html("");
  var menu_left='<a id="back_detail_realtime" href="#" class="icon-only"><i class="icon icon-back"></i>Back</a>';
  $$("#left_index").append(menu_left);
  */
var datereport=$$(this).attr("datereport");
var sawId=$$(this).attr("sawId");
getdetailrealtime(sawId,datereport);
});

function getdetailrealtime(sawId,datereport) {
  $$("#content_detail").html('');
mainView.router.load({pageName: 'list',ignoreCache:true});
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
  //var dataarray=data[0];
  $$.each(data[0], function(i, field){
    //console.log(i);
    //console.log(field);

    content+='\
    <li>\
         <a id="listdetail" href="" class="item-link item-content"\
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


/*
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
*/
$$(document).on('page:back', '.page[data-page="list"]', function (e) {
  $$("#content_detail").html('');
});
$$(document).on('page:back', '.page[data-page="listdetail"]', function (e) {
  $$("#content_detail_id").html('');
});
$$(document).on("click", "#back_detail_realtime", function() {
//mainView.router.load({pageName: 'index'});
$$("#left_index").html("");
var menu_left='<a href="#" class="open-panel link icon-only"><i class="f7-icons" style="font-size: 30px;">bars</i></a>';
$$("#left_index").append(menu_left);
$$("#content").css('display', 'block');
$$("#right_index").css('display', 'block');
$$("#content_detail").html('');
});
$$(document).on("click", "#detail_realtime_id", function() {
$$("#content_detail_id").html('');
$$("#left_index").html("");
var menu_left='<a id="back_detail_realtime" href="#" class="icon-only"><i class="icon icon-back"></i>Back</a>';
$$("#left_index").append(menu_left);
$$("#content_detail").css('display', 'block');
});


$$(document).on("click", "#listdetail", function() {

mainView.router.load({pageName: 'listdetail',ignoreCache:true});

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
//$$("#content_detail").css('display', 'none');
//$$("#left_index").html("");
//var menu_left='<a id="detail_realtime_id" href="#" class="icon-only"><i class="icon icon-back"></i>Back</a>';
//$$("#center_detail_id").html('');
//$$("#content_detail_id").html('');
//$$("#center_detail_id").append(cus_name);

var content='';
content+='\
<div class="card">\
    <div class="card-header">'+cus_name+'</div>\
    <div class="card-content">\
';

var url = "http://"+hosturl+"/api/report_realtime_detail_id.php";
var array_img=[];
$$.getJSON( url, {
    sawId:sawId,
    weight_no:weight_no
  }
,function( data ) {

if (data[0]!='no') {
$$.each(data[0], function(i, field){
array_img.push("http://afm.revocloudserver.com/uploadimage/"+field.file_image);
});//each


  var myPhotoBrowserStandalone = myApp.photoBrowser({
      photos :array_img
  });
}
var check=array_img.length;
if (check>0) {
content+='<div class="col-33"><a href="#" class="button pb-standalone">รูปภาพ</a></div>';
}
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
//Open photo browser on click
$$('.pb-standalone').on('click', function () {
    myPhotoBrowserStandalone.open();
});


});//getjson


});





$$(document).on("click", "#month_report", function() {
  $$("#content").html("");
  $$("#right_index").html("");
  $$(".navbar").css('display', 'block');
  $$("#contenthead").css('display', 'none');
  $$(".page-content").removeClass("login-screen-content");
  var proname=$$(this).attr("proname");
  var Arrray_SawID=getSawID();
var saw_id=Arrray_SawID[0].sawId;
  var calendar='\
  <i id="calendars" style="font-size: 36px;" class="f7-icons" report="month_report" proname="'+proname+'" saw_id="'+saw_id+'">calendar</i>\
  ';
  $$("#right_index").append(calendar);
  var formattedDate = new Date();
  var d = formattedDate.getDate();
  var m =  formattedDate.getMonth();
  m += 1;  // JavaScript months are 0-11
  var y = formattedDate.getFullYear();
  var datereport=y + "-" + m + "-" + d;
  var datereportshow=d + "/" + m + "/" + y;
var report="month_report";
tabs(Arrray_SawID,report,datereport,proname);
getDataPro_name(datereport,proname,saw_id);

});

function getMonth(month) {
  var array=[
    'มกราคม',
    'กุมภาพันธ์',
    'มีนาคม',
    'เมษายน',
    'พฤษภาคม',
    'มิถุนายน',
    'กรกฎาคม',
    'สิงหาคม',
    'กันยายน',
    'ตุลาคม',
    'พฤศจิกายน',
    'ธันวาคม',
  ];
  return array[month-1];
}

function getMonthraw(month) {
  var array=[
    'มกราคม',
    'กุมภาพันธ์',
    'มีนาคม',
    'เมษายน',
    'พฤษภาคม',
    'มิถุนายน',
    'กรกฎาคม',
    'สิงหาคม',
    'กันยายน',
    'ตุลาคม',
    'พฤศจิกายน',
    'ธันวาคม',
  ];
  var month = array.indexOf(month);
  return month;
}
function getDataPro_name(datenow,proname,saw_id) {
  $$("#content").html("");
  $$("#contenthead").css('display', 'none');
  var datereport=datenow;
  var array_day = datereport.split("-");
  var datereportshow=array_day[2] + "/" + array_day[1] + "/" + array_day[0];
  var m=array_day[1];
  var y=array_day[0];
  var email =localStorage.email;
  var month_name=getMonth(m);
  console.log(month_name);
  var content='\
  <div class="content-block-title">รายงาน '+proname+' ประจำเดือน '+month_name+' ปี '+y+'</div>\
  ';
  $$("#content").append(content);
  var url = "http://"+hosturl+"/api/report_data_pro_name.php";
  var table='';
      table+='\
  <div class="data-table card">\
  <table>\
    <thead>\
      <tr style="position: sticky">\
        <th style="text-align: center">วันที่</th>\
        <th style="text-align: center">นำเข้า</th>\
        <th style="text-align: center">ขาย</th>\
        <th style="text-align: center">เลื่อย</th>\
        <th style="text-align: center">คงเหลือ</th>\
        <th style="text-align: center">สูญเสีย</th>\
      </tr>\
    </thead>\
    <tbody>\
  ';
  var sum_wood_income=0;
  var sum_wood_sale=0;
  var sum_timber_saw=0;
  var sum_total=0;
  var sum_losts=0;
  $$.getJSON( url, {
      saw_id:saw_id,
      datereport:datereport,
      pro_name:proname
    }
  ,function( data ) {
  var num=1;
  $$.each(data, function(i, field){
    var modceck=num%2;
    if (modceck==0) {
      table+='\
      <tr style="background-color:#f1f1f1">';
    }else {
      table+='\
      <tr>';
    }
    var wood_income=checknull(field.wood_income);
    var wood_sale=checknull(field.wood_sale);
    var timber_saw=checknull(field.timber_saw);
    var total=checknull(field.total);
    var losts=checknull(field.losts);
    if (wood_income !=0 || wood_sale !=0 || timber_saw !=0 || total !=0 || losts !=0) {
    console.log("yes");

    table+='\
      <td style="text-align: center">'+num+'</td>\
      <td style="text-align: center">'+Number(wood_income).toLocaleString()+'</td>\
      <td style="text-align: center">'+Number(wood_sale).toLocaleString()+'</td>\
      <td style="text-align: center">'+Number(timber_saw).toLocaleString()+'</td>\
      <td style="text-align: center">'+Number(total).toLocaleString()+'</td>\
      <td style="text-align: center">'+Number(losts).toLocaleString()+'</td>\
    </tr>\
    ';
    sum_wood_income+=parseFloat(wood_income);
    sum_wood_sale+=parseFloat(wood_sale);
    sum_timber_saw+=parseFloat(timber_saw);
    sum_total+=parseFloat(total);
    sum_losts+=parseFloat(losts);
    }
    num++;
});//each
table+='\
<tr style="background-color:#c4e487">\
<td style="text-align: center">รวม</td>\
<td style="text-align: center">'+sum_wood_income.toLocaleString()+'</td>\
<td style="text-align: center">'+sum_wood_sale.toLocaleString()+'</td>\
<td style="text-align: center">'+sum_timber_saw.toLocaleString()+'</td>\
<td style="text-align: center">'+sum_total.toLocaleString()+'</td>\
<td style="text-align: center">'+sum_losts.toLocaleString()+'</td>\
</tr>\
</tbody>\
</table>\
</div>\
';
$$("#content").append(table);
});//getJSON

}//function

function checknull(data) {
  var result='';

  if (!data) {
    result=0;

  }else {
    result=data;
  }
  return result;
}



$$(document).on("click", "#profit", function() {
  $$("#content").html("");
  $$("#right_index").html("");
  $$("#tab").html("");
  $$(".navbar").css('display', 'block');
  $$("#contenthead").css('display', 'none');
  $$(".page-content").removeClass("login-screen-content");
  var proname=$$(this).attr("proname");
  var Arrray_SawID=getSawID();
var saw_id=Arrray_SawID[0].sawId;
  var calendar='\
  <i id="calendars" style="font-size: 36px;" class="f7-icons" report="profit_report" saw_id="'+saw_id+'">calendar</i>\
  ';
  $$("#right_index").append(calendar);
  var formattedDate = new Date();
  var d = formattedDate.getDate();
  var m =  formattedDate.getMonth();
  m += 1;  // JavaScript months are 0-11
  var y = formattedDate.getFullYear();
  var datereport=y + "-" + m + "-" + d;
  var datereportshow=d + "/" + m + "/" + y;
var report="profit_report";
tabs(Arrray_SawID,report,datereport,proname);
  getDataProFit(datereport,saw_id);
});//click

function getDataProFit(datenow,saw_id) {
  $$("#content").html("");
  $$("#contenthead").css('display', 'none');
  var datereport=datenow;
  var array_day = datereport.split("-");
  var datereportshow=array_day[2] + "/" + array_day[1] + "/" + array_day[0];
  var m=array_day[1];
  var y=array_day[0];
  var email =localStorage.email;
  var month_name=getMonth(m);
  console.log(month_name);
  var content='\
  <div class="content-block-title">กำไรขาดทุน ประจำเดือน '+month_name+' ปี '+y+'</div>\
  ';
  $$("#content").append(content);
  var url = "http://"+hosturl+"/api/report_data_profit.php";
  var table='';
      table+='\
  <div class="data-table card">\
  <table>\
    <thead>\
      <tr style="position: sticky">\
        <th style="text-align: center">วันที่</th>\
        <th style="text-align: center">รายได้</th>\
        <th style="text-align: center">รายจ่าย</th>\
        <th style="text-align: center">กำไร</th>\
        <th style="text-align: center">รายจ่ายคงที่</th>\
        <th style="text-align: center">กำไรสุทธิ</th>\
      </tr>\
    </thead>\
    <tbody>\
  ';
  var sum_incoming_total=0;
  var sum_outcoming_total=0;
  var sum_gross_profit_total=0;
  var sum_costs_total=0;
  var sum_profit_loss_total=0;
  $$.getJSON( url, {
      saw_id:saw_id,
      datereport:datereport,
    }
  ,function( data ) {
  console.log(data);
  $$.each(data, function(i, field){
    var modceck=(i+1)%2;
    if (modceck==0) {
      table+='\
      <tr style="background-color:#f1f1f1">';
    }else {
      table+='\
      <tr>';
    }
    table+='\
      <td style="text-align: center">'+Number(field.date).toLocaleString()+'</td>\
      <td style="text-align: center">'+Number(field.incoming_total).toLocaleString()+'</td>\
      <td style="text-align: center">'+Number(field.outcoming_total).toLocaleString()+'</td>\
      <td style="text-align: center">'+Number(field.gross_profit_total).toLocaleString()+'</td>\
      <td style="text-align: center">'+Number(field.costs_total).toLocaleString()+'</td>\
      <td style="text-align: center">'+Number(field.profit_loss_total).toLocaleString()+'</td>\
    </tr>\
    ';
    sum_incoming_total+=parseFloat(field.incoming_total);
    sum_outcoming_total+=parseFloat(field.outcoming_total);
    sum_gross_profit_total+=parseFloat(field.gross_profit_total);
    sum_costs_total+=parseFloat(field.costs_total);
    sum_profit_loss_total+=parseFloat(field.profit_loss_total);
  });//each
  table+='\
  <tr style="background-color:#c4e487">\
  <td style="text-align: center">รวม</td>\
  <td style="text-align: center">'+sum_incoming_total.toLocaleString()+'</td>\
  <td style="text-align: center">'+sum_outcoming_total.toLocaleString()+'</td>\
  <td style="text-align: center">'+sum_gross_profit_total.toLocaleString()+'</td>\
  <td style="text-align: center">'+sum_costs_total.toLocaleString()+'</td>\
  <td style="text-align: center">'+sum_profit_loss_total.toLocaleString()+'</td>\
</tr>\
  </tbody>\
  </table>\
  </div>\
  ';
  $$("#content").append(table);
  });//getJSON

}//function


$$(document).on("click", "#performance", function() {
  $$("#content").html("");
  $$("#right_index").html("");
  $$(".navbar").css('display', 'block');
  $$("#contenthead").css('display', 'none');
  $$(".page-content").removeClass("login-screen-content");
  var Arrray_SawID=getSawID();
var saw_id=Arrray_SawID[0].sawId;
  var calendar='\
  <i id="calendars" style="font-size: 36px;" class="f7-icons" report="performance_report" proname=" " saw_id="'+saw_id+'">calendar</i>\
  ';
  $$("#right_index").append(calendar);
  var formattedDate = new Date();
  var d = formattedDate.getDate();
  var m =  formattedDate.getMonth();
  m += 1;  // JavaScript months are 0-11
  var y = formattedDate.getFullYear();
  var datereport=y + "-" + m + "-" + d;

var report="performance_report";
tabs(Arrray_SawID,report,datereport,'');
  getDataPerformance(datereport,saw_id);
});//click

function getDataPerformance(datenow,saw_id) {
  $$("#content").html("");
  $$("#contenthead").css('display', 'none');
  var datereport=datenow;
  var array_day = datereport.split("-");
  var datereportshow=array_day[2] + "/" + array_day[1] + "/" + array_day[0];
  var m=array_day[1];
  var y=array_day[0];
  var email =localStorage.email;
  var month_name=getMonth(m);
  console.log(month_name);
  var content='\
  <div class="content-block-title">ประสิทธิภาพ เดือน '+month_name+' ปี '+y+'</div>\
  <div class="card">\
      <div class="card-content">\
          <div class="card-content-inner"><canvas id="myChart" width="600" height="400"></canvas></div>\
      </div>\
      </div>\
  ';
  $$("#content").append(content);
  var url = "http://"+hosturl+"/api/report_data_performance.php";
  var table='';
      table+='\
  <div class="data-table card">\
  <table>\
    <thead>\
      <tr style="position: sticky">\
        <th style="text-align: center">วันที่</th>\
        <th style="text-align: center">ปริมาณการผลิต/เป้าหมาย</th>\
        <th style="text-align: center">ab/เป้าหมาย</th>\
        <th style="text-align: center">ab+c/เป้าหมาย</th>\
      </tr>\
    </thead>\
    <tbody>\
  ';
  var sum_volume_product=0;
  var sum_volume_product_goal=0;
  var sum_ab=0;
  var sum_ab_goal=0;
  var sum_ab_c=0;
  var sum__ab_c_goal=0;

  $$.getJSON( url, {
      saw_id:saw_id,
      datereport:datereport,
    }
  ,function( data ) {
  console.log(data);
  $$.each(data, function(i, field){
    sum_volume_product+=parseFloat(field.volume_product);
    sum_volume_product_goal+=parseFloat(field.volume_product_goal);
    sum_ab+=parseFloat(field.ab);
    sum_ab_goal+=parseFloat(field.ab_goal);
    sum_ab_c+=parseFloat(field.ab_c);
    sum__ab_c_goal+=parseFloat(field.ab_c_goal);
    var modceck=(i+1)%2;
    if (modceck==0) {
      table+='\
      <tr style="background-color:#f1f1f1">';
    }else {
      table+='\
      <tr>';
    }
    table+='\
      <td style="text-align: center">'+field.date+'</td>\
      <td style="text-align: center">'+Number(field.volume_product).toLocaleString()+'/'+Number(field.volume_product_goal).toLocaleString()+'</td>\
      <td style="text-align: center">'+Number(field.ab).toLocaleString()+'/'+Number(field.ab_goal).toLocaleString()+'</td>\
      <td style="text-align: center">'+Number(field.ab_c).toLocaleString()+'/'+Number(field.ab_c_goal).toLocaleString()+'</td>\
    </tr>\
    ';
  });//each
  table+='\
    <tr style="background-color:#c4e487">\
    <td style="text-align: center">รวม</td>\
    <td style="text-align: center">'+sum_volume_product.toLocaleString()+'/'+sum_volume_product_goal.toLocaleString()+'</td>\
    <td style="text-align: center">'+sum_ab.toLocaleString()+'/'+sum_ab_goal.toLocaleString()+'</td>\
    <td style="text-align: center">'+sum_ab_c.toLocaleString()+'/'+sum__ab_c_goal.toLocaleString()+'</td>\
  </tr>\
  </tbody>\
  </table>\
  </div>\
  ';
  $$("#content").append(table);
  console.log(sum__ab_c_goal.toLocaleString());


  var backgroundColor = [
  	'rgb(255, 99, 132)',
  	'rgb(54, 162, 235)'
  ];
  var label=["Volume", "AB", "AB+C"];
  var data1=[sum_volume_product,sum_ab,sum_ab_c];
  var data2=[sum_volume_product_goal,sum_ab_goal,sum__ab_c_goal];

  var datasets=[
{
  						label: 'ผลการผลิต',
  						data:data1,
  						backgroundColor:backgroundColor[0]
  				},
  						{label: 'เป้าหมาย',
  						data:data2 ,
  						backgroundColor:backgroundColor[1]
  								}
];

  console.log(label);
  console.log(datasets);
grap(label,datasets);

  });//getJSON


  function grap(label,datasets) {
    var ctx = document.getElementById("myChart").getContext('2d');
  var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: label,
          datasets:datasets
      },
      options: {
        tooltips: {
    mode: 'index',
    intersect: false
},
responsive: true,
          scales: {
            xAxes: [{
                                       stacked: true,
                                   }],
                                   yAxes: [{
                                       stacked: true
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

}//function



function getSawID() {
var email =localStorage.email;
var result="";
var url = "http://"+hosturl+"/api/get_user.php?email="+email;
/*
result=$$.getJSON( url, {
    email:email,
  }
);
return result;
*/
$.ajax({
   url: url,
   type: 'get',
   dataType: 'json',
   async: false,
   success: function(data) {
       result = data;
   }
});
return result;
}


function tabs(data,report,date,proname) {
  $$("#tab").html("");
  $$("#tabs").html("");
  $$("#tab").css('display', 'block');
  var content='\
  <div class="toolbar-inner">\
  ';
  var content2='';
  $$.each(data, function(i, field){
    if (i==0) {
      content+='\
      <a id="tab_menu" datetime="'+date+'" report="'+report+'" saw_id="'+field.sawId+'" proname="'+proname+'" href="#tab'+(i+1)+'" class="tab-link active">\
      <i class="icon demo-icon-1"></i>'+field.shortname+'\
      </a>\
      ';
      content2+='\
      <div id="tab'+(i+1)+'" class="tab active">\
      </div>\
      ';
      var add_saw_id=$$("#calendars").attr("saw_id",field.sawId);
    }else{
      content+='\
      <a id="tab_menu" datetime="'+date+'" report="'+report+'" saw_id="'+field.sawId+'" proname="'+proname+'" href="#tab'+(i+1)+'" class="tab-link">\
      <i class="icon demo-icon-1"></i>'+field.shortname+'\
      </a>\
      ';
      content2+='\
      <div id="tab'+(i+1)+'" class="tab">\
      </div>\
      ';
    }

  });
    content+='\
    </div>\
    ';
    $$("#tab").append(content);
    $$("#tabs").append(content2);
}


$$(document).on("click", "#tab_menu", function() {
var report=$$(this).attr("report");
var datereport=$$(this).attr("datetime");
var saw_id=$$(this).attr("saw_id");
var proname=$$(this).attr("proname");
//var add_saw_id=$$("#calendars").attr("saw_id",saw_id);

var add_saw_id=$("#calendars").removeAttr("style");
$$("#right_index").html("");
var calendar='\
<i id="calendars" style="font-size: 36px;" class="f7-icons" report="'+report+'" proname="'+proname+'" saw_id="'+saw_id+'">calendar</i>\
';
$$("#right_index").append(calendar);
switch (report) {
  case 'performance_report':
    getDataPerformance(datereport,saw_id);
    break;
    case 'profit_report':
      getDataProFit(datereport,saw_id);
      break;
      case 'month_report':
        getDataPro_name(datereport,proname,saw_id);
        break;
  default:

}
});//click
