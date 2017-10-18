checkcookies();
function checkcookies() {
if(localStorage.email){
gethome();
}else{
getlogin();
}
}//checkcookies

function getlogin() {
$$("#content").html("");
$$(".navbar").css('display', 'none');
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
gethome();
}


});

});

});//click login

function gethome() {
$$("#content").html("");
$$(".navbar").css('display', 'block');
$$(".page-content").removeClass("login-screen-content");
var content=localStorage.email;
$$("#content").append(content);
getRealtimeTransaction();
}//gethome

function getRealtimeTransaction() {
var content= '<canvas id="myChart" width="600" height="400"></canvas>';
$$("#content").append(content);
garphrealtime();
}//RealtimeTransaction

function garphrealtime() {
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
var datasets=[];
  $$.getJSON( url, {
      email:email
    }
  ,function( data ) {
    //  console.log(data);
  $$.each(data, function(i, field){
if (field[0].transaction_count>0) {
  var a={
              label: field[0].shortname,
              data: [field[0].weight_total,field[0].price_total,field[0].transaction_count],
              backgroundColor:backgroundColor[i],
              borderColor:borderColor[i],
              borderWidth: 1
          };
  datasets.push(a);
}    
});
graprealtime(datasets);
});

}

function graprealtime(datasets) {
  var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Wood", "Price", "Transaction"],
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
}
