/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
 var database = window.localStorage.getItem('database');
 //$('#credentials').html('<br/><br/>User: '+ window.localStorage.getItem('ls_userid') + '| Branch: ' + window.localStorage.getItem('database'));

//bluetooth printing
var BTPrinter = {
   list: function(fnSuccess, fnError){
      exec(fnSuccess, fnError, "BluetoothPrinter", "list", []);
   },
   connect: function(fnSuccess, fnError, name){
      exec(fnSuccess, fnError, "BluetoothPrinter", "connect", [name]);
   },
   disconnect: function(fnSuccess, fnError){
      exec(fnSuccess, fnError, "BluetoothPrinter", "disconnect", []);
   },
   print: function(fnSuccess, fnError, str){
      exec(fnSuccess, fnError, "BluetoothPrinter", "print", [str]);
   },
   printText: function(fnSuccess, fnError, str){
      exec(fnSuccess, fnError, "BluetoothPrinter", "printText", [str]);
   },
    printImage: function(fnSuccess, fnError, str){
      exec(fnSuccess, fnError, "BluetoothPrinter", "printImage", [str]);
    },
   printPOSCommand: function(fnSuccess, fnError, str){
      exec(fnSuccess, fnError, "BluetoothPrinter", "printPOSCommand", [str]);
   }
};


var app = {
    // Application Constructor
    initialize: function() {
        document.addEventListener('deviceready', this.onDeviceReady.bind(this), false);
    },

    // deviceready Event Handler
    //
    // Bind any cordova events here. Common events are:
    // 'pause', 'resume', etc.
    onDeviceReady: function() {
        this.receivedEvent('deviceready');
        var success = function(message) {
        alert(message);
        }

        var failure = function() {
            alert("Error calling Hello Plugin");
        }

        //hello.greet("World", success, failure);

       
    },

    // Update DOM on a Received Event
    receivedEvent: function(id) {
        var parentElement = document.getElementById(id);
        var listeningElement = parentElement.querySelector('.listening');
        var receivedElement = parentElement.querySelector('.received');

        listeningElement.setAttribute('style', 'display:none;');
        receivedElement.setAttribute('style', 'display:block;');

        console.log('Received Event: ' + id);
    }
};

app.initialize();




var app = {
    sendSms: function() {
        var number = '0726738023'; /* iOS: ensure number is actually a string */
        var message = 'Hi Prince .how are you doing?';
        console.log("number=" + number + ", message= " + message);

        //CONFIGURATION
        var options = {
            replaceLineBreaks: false, // true to replace \n by a new line, false by default
            android: {
                intent: ''  // send SMS with the native android SMS messaging
            }
        };

        var success = function () { alert('Message sent successfully'); };
        var error = function (e) { alert('Message Failed:' + e); };
        sms.send(number, message, options, success, error);
    }
};

function listprinters(){



        
       BTPrinter.list(function(data){
        //list of printer in data array
        data=data.toString()
        var myStringArray = data.split(",");
        var arrayLength = myStringArray.length;
         $("#printerlist").html(" <h2 class='page_title'>DISCOVERED DEVICES</h2>");
        for (var i = 0; i < arrayLength; i++) {
              $("#printerlist").append("<a class='item' id='printer" + i + "' ondblclick='disconnectprinter(\"" + myStringArray[i] + "\")' onclick='connectprinter(\"" + myStringArray[i] + "\"," + i + ")'><h2>" + myStringArray[i] + " </h2><span id='loader" + i + "'></span></a><br/>");
             
            //Do something
        }

        },function(err){
             alert(err);
        })

        

}

function connectprinter(PrinterName,id){
       //$("#loader"+id).html('<img id="img-spinner" src="img/loader.gif" style="" alt="Loading"/>');
       BTPrinter.connect(function(data){
       alert(data)
       $(".item").css('background', '#fff');
       $("#printer"+id).css('background', '#2ad5dc');
        $("#printerlist").append("<input type='button' name='submit' class='form_submit' id='submit' style='font-size:15px' value='PRINT SAMPLE TEXT' onclick='printsimpletext()'/>");
       },function(err){
        alert(err)
      }, PrinterName);
     


}


function disconnectprinter(PrinterName){

      BTPrinter.disconnect(function(data){
       alert(data)
      },function(err){
        alert(err)
      }, PrinterName)

}

function printsimpletext(){
    //var text="PAID\n\nTHIKA BUSINESS CENTRE\n\nP.O Box 1387 THIKA\nTel: 067-2221435\n\nCASH PAYMENT RECEIPT\n\nReceipt No: 5\n\nCheck-in Time: 10/07/2017 09:54 PM\n\nCheckOut Time: 10/07/2017 10:20 PM\n\nTime Spent: 26 Minutes\n\nAmount Paid: 50.00\n\nIn Words: fifty KES only.\n\nRegn: KCD 985J\n\nOfficer: admin\n\nSystem Developers: QET SYSTEMS\n\n\n\n\n\n";
    var text="SAMPLE TEST\n\nTHIS IS A SAMPLE TEXT\n\n\n\n\n\n";
   
    BTPrinter.printText(function(data){
    alert(data)
    },function(err){
        alert(err)
    }, text);

}

function printreceipt(text){
    BTPrinter.printText(function(data){
    alert(data)
    },function(err){
        alert(err)
    }, text);


}
//usb printing

document.addEventListener("deviceready", onDeviceReady, false);

// Cordova Device Ready.
function onDeviceReady() {

cordova.plugins.printer.isAvailable(
    //Check whether the printer is available or not.
    function (isAvailable) {
         //Enter the page location.
         var page = location.href;
         cordova.plugins.printer.print(page, 'Document.html', function () {
         alert('printing finished or canceled')
});
    }
);

}

function printusbpage(text){

      
      /*var htmlContent = "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Title</title> <link href='css/print.css' rel='stylesheet' /></head><body><div>test print</div></body></html>"

      msg='message';
      var options = {
          name: 'print-job', // printjob name
          printerId: $scope.PrinterUrl, // network url of the printer to use (iOS only)
          //duplex: false, // default true (double sided) (iOS only)
          landscape: false, // default false (portrait)
          graystyle: true, // prints black and white (default), but true has better performance
          bounds: {left:0, top:0, width:0, height:0}, // size and position of the print view (iPad only)
          hidePaperFormat: true,
          border: false,
          hidePageRange: true
        };
        */

     /* cordova.plugins.printer.print(htmlContent, options).then(function(msg){
        console.log('Print Ok: ' + msg);
      });
      */
      /*
  cordova.plugins.printer.print(text, { duplex: 'long' }, function (res) {
    alert(res ? 'Done' : 'Canceled');
  });
*/

cordova.plugins.printer.pick(function (uri) {
    cordova.plugins.printer.print(text, { printerId: uri });
});

}

function checkusbprinter(){


  /**
 * Checks if the printer service is available (iOS)
 * or if printer services are installed and enabled (Android).
 *
 * @param {Function} callback
 *      A callback function
 * @param {Object} scope
 *      Optional scope of the callback
 *      Defaults to: window
 */
cordova.plugins.printer.check(function (available, count) {
    alert(available ? 'Found ' + count + ' services' : 'No');
});
}

function pickusbprinter(){

  /**
 * Displays system interface for selecting a printer.
 *
 * @param {Function} callback
 *      A callback function
 */
cordova.plugins.printer.pick(function (uri) {
    alert(uri ? uri : 'Canceled');
});


}
// Convert numbers to words
// copyright 25th July 2006, by Stephen Chapman http://javascript.about.com
// permission to use this Javascript on your web page is granted
// provided that all of the code (including this copyright notice) is
// used exactly as shown (you can change the numbering system if you wish)

// American Numbering System
var th = ['','thousand','million', 'billion','trillion'];
// uncomment this line for English Number System
// var th = ['','thousand','million', 'milliard','billion'];

var dg = ['zero','one','two','three','four', 'five','six','seven','eight','nine']; var tn = ['ten','eleven','twelve','thirteen', 'fourteen','fifteen','sixteen', 'seventeen','eighteen','nineteen']; var tw = ['twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety']; function toWords(s){s = s.toString(); s = s.replace(/[\, ]/g,''); if (s != parseFloat(s)) return 'not a number'; var x = s.indexOf('.'); if (x == -1) x = s.length; if (x > 15) return 'too big'; var n = s.split(''); var str = ''; var sk = 0; for (var i=0; i < x; i++) {if ((x-i)%3==2) {if (n[i] == '1') {str += tn[Number(n[i+1])] + ' '; i++; sk=1;} else if (n[i]!=0) {str += tw[n[i]-2] + ' ';sk=1;}} else if (n[i]!=0) {str += dg[n[i]] +' '; if ((x-i)%3==0) str += 'hundred ';sk=1;} if ((x-i)%3==1) {if (sk) str += th[(x-i-1)/3] + ' ';sk=0;}} if (x != s.length) {var y = s.length; str += 'point '; for (var i=x+1; i<y; i++) str += dg[n[i]] +' ';} return str.replace(/\s+/g,' ');}

// Convert figures to money values
Number.prototype.formatMoney = function(c, d, t){
var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 }; 
// Initialize your app
var myApp = new Framework7({
    animateNavBackIcon: true,
    animatePages:false,
    precompileTemplates: true,
	swipeBackPage: true,
	swipeBackPageThreshold: 1,
	pushState: false,
    template7Pages: true,
    cache: false,
    cacheDuration: 0
});


// Export selectors engine
var $$ = Dom7;

// Add main View
var mainView = myApp.addView('.view-main', {
    // Enable dynamic Navbar
    dynamicNavbar: false
});

function setrightsli(){
   var rights = JSON.parse(localStorage.getItem("rights")); 
   
  $(".main-nav ul > li")
    .css('opacity', '0')
    .each(function(index, item) {
      var fieldName=$(item).attr('id');len=fieldName.length-6;code=fieldName.substr(6, len);
      if(rights[code]=='YES'){setTimeout(function() {$(item).fadeTo('fast',1,"easeInOutCirc");}, index*75);}
  }); 
  $(".main-nav ul > li span")
      .css('opacity', '0')
      .each(function(index, item) {
      setTimeout(function() {$(item).animate({'left': '0px', 'opacity':1},1,"easeInOutCirc")}, index*75);
     }); 
  

}

function testback(){
  view.router.reloadPreviousPage
}



jQuery(document).ready(function() {
"use strict"; 

	$(".logo").animate({'top': '20px'},'slow',"easeInOutCirc");
	$(".cartitems").delay(1000).animate({'width': '30px', 'height': '30px', 'top':'10px', 'right':'10px', 'opacity':1},1000,"easeOutBounce");

	  setrightsli();
   
	
    
							
});





$$(document).on('ajaxStart',function(e){myApp.showIndicator();});
$$(document).on('ajaxComplete',function(){myApp.hideIndicator();});	

$$('.popup').on('opened', function () {
  $(".close_loginpopup_button a").animate({'right':'10px', 'opacity':1},'slow',"easeInOutCirc");
});
$$('.popup').on('closed', function () {
  $(".close_loginpopup_button a").animate({'right':'0px', 'opacity':0},'slow',"easeInOutCirc");
});


function setCursor(node){
	$('#'+node).focus();
	pos=$('#'+node).val().length;
	//alert(pos);
    node = (typeof node == "string" || node instanceof String) ? document.getElementById(node) : node;

    if(!node){
        return false;
    }else if(node.createTextRange){
        var textRange = node.createTextRange();
        textRange.collapse(true);
        textRange.moveEnd(pos);
        textRange.moveStart(pos);
        textRange.select();
        return true;
    }else if(node.setSelectionRange){
        node.setSelectionRange(pos,pos);
        return true;
    }

    return false;
}

function loadposts(){
	 $(".posts li").hide();	
		size_li = $(".posts li").size();
		x=4;
		$('.posts li:lt('+x+')').show();
		$('#loadMore').click(function () {
			x= (x+1 <= size_li) ? x+1 : size_li;
			$('.posts li:lt('+x+')').show();
			if(x == size_li){
				$('#loadMore').hide();
				$('#showLess').show();
			}
		});						   
									   
		$("ul.posts > li div.post_date")
			.css('opacity', '0')
			.each(function(index, item) {
				setTimeout(function() {
					$(item).animate({'left':'0px', 'opacity':1},800,"easeInOutCirc");
				}, index*175);
		});	
		$("ul.posts > li div.post_title")
			.css('opacity', '0')
			.each(function(index, item) {
				setTimeout(function() {
					$(item).animate({'right':'0px', 'opacity':1},800,"easeInOutCirc");
				}, index*175);
		});	
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
function setpaymentticket(ticketno){
	localStorage.setItem("paymentticket",ticketno);
}
myApp.onPageInit('blog', function (page) {
    window.location.hash="blog";  
var param = '';
var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=5&param="+param+"&database="+database;
        $.getJSON(url, function(result) {
            console.log(result);
            $.each(result, function(i, field) {
                var regn = field.regn;
                 xx='';
                 if(field.reserved==1){xx='background:#37bc9b';}
               	$(".post_entry").append("<a onclick='setpaymentticket("+field.ticketno+")' href='payments2.html'><div class='post_date' style='cursor:pointer; "+ xx + "'><span class='day'>" + field.regn + "</span><span class='month' style='color:#333'>" + field.checkindate + field.checkintime + "</span></div></a>");
            });

        loadposts()
        $('#searchtickets').focus();

        });	



})


function todaysales(){
 
    window.location.hash="#output"; 
    $('#accdiv').hide();
    $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
    data:{id:1,code:1,user:username},
    success:function(data){
    $('#reportdiv').html(data);
    }
    }); 



}

function salesrep(code){
    var a=1;
    var from = $('#bfrom').val();
    var to = $('#bto').val();
    var view = $('input[name=bviewall]:checked').val();
    if(!(view)){view=0}
    if((from==''||to=='')&&view==0){
    swal("Error", "Enter both Start and End dates!", "error");
    }
    else{

       window.location.hash="#output"; 
      $('#accdiv').hide();

      $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
      $.ajax({
      url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
      data:{id:1,code:2,user:username,d1:from,d2:to},
      success:function(data){
      $('#reportdiv').html(data);
      }
      }); 
    }
    
}



function todayrefunds(){
 
    window.location.hash="#output"; 
    $('#accdiv').hide();
    $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
    data:{id:2,code:1,user:username},
    success:function(data){
    $('#reportdiv').html(data);
    }
    }); 



}

function refundsrep(code){
    var a=1;
    var from = $('#from4').val();
    var to = $('#to4').val();
    var view = $('input[name=viewall4]:checked').val();
    if(!(view)){view=0}
    if((from==''||to=='')&&view==0){
    swal("Error", "Enter both Start and End dates!", "error");
    }
    else{
       window.location.hash="#output"; 
    $('#accdiv').hide();
      $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
      $.ajax({
      url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
      data:{id:2,code:2,user:username,d1:from,d2:to},
      success:function(data){
      $('#reportdiv').html(data);
      }
      }); 
    }
    
}


function pricelist(){
  window.location.hash="#output"; 
    $('#accdiv').hide();
    $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
    data:{id:3,code:1,user:username},
    success:function(data){
    $('#reportdiv').html(data);
    }
    }); 

}

function valuation(){
  window.location.hash="#output"; 
    $('#accdiv').hide();
    $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
    data:{id:4,code:1,user:username},
    success:function(data){
    $('#reportdiv').html(data);
    }
    }); 

}


function stocktrackrep(code){
    var a=1;
    var from = $('#from5').val();
    var to = $('#to5').val();
    var view = $('input[name=viewall5]:checked').val();
    if(!(view)){view=0}
    if((from==''||to=='')&&view==0){
    swal("Error", "Enter both Start and End dates!", "error");
    }
    else{
 window.location.hash="#output"; 
    $('#accdiv').hide();
      $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
      $.ajax({
      url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
      data:{id:5,code:2,user:username,d1:from,d2:to},
      success:function(data){
      $('#reportdiv').html(data);
      }
      }); 
    }
    
}

function sysusers(){
  window.location.hash="#output"; 
    $('#accdiv').hide();
   
    $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
    data:{id:7,code:1,user:username},
    success:function(data){
    $('#reportdiv').html(data);
    }
    }); 



}

function todayexpenses(){
 
    window.location.hash="#output"; 
    $('#accdiv').hide();
    $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
    data:{id:8,code:1,user:username},
    success:function(data){
    $('#reportdiv').html(data);
    }
    }); 



}




function exprep(code){
    var a=1;
    var name = $('#expenseledger').val();
    var from = $('#from6').val();
    var to = $('#to6').val();
    var view = $('input[name=viewall6]:checked').val();
    if(!(view)){view=0}
    if((from==''||to=='')&&view==0){
    swal("Error", "Enter both Start and End dates!", "error");
    }
    else{
 window.location.hash="#output"; 
    $('#accdiv').hide();
      $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
      $.ajax({
      url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
      data:{id:8,code:2,user:username,d1:from,d2:to,name:name},
      success:function(data){
      $('#reportdiv').html(data);
      }
      }); 
    }
    
}


function todaybank(){
  window.location.hash="#output"; 
    $('#accdiv').hide();
   
    $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
    data:{id:9,code:1,user:username},
    success:function(data){
    $('#reportdiv').html(data);
    }
    }); 



}

function bankrep(code){
    var a=1;
    var name = $('#bankledger').val();
    var from = $('#from7').val();
    var to = $('#to7').val();
    var view = $('input[name=viewall7]:checked').val();
    if(!(view)){view=0}
    if((from==''||to=='')&&view==0){
    swal("Error", "Enter both Start and End dates!", "error");
    }
    else{
 window.location.hash="#output"; 
    $('#accdiv').hide();
      $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
      $.ajax({
      url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
      data:{id:9,code:2,user:username,d1:from,d2:to,name:name},
      success:function(data){
      $('#reportdiv').html(data);
      }
      }); 
    }
    
}


function chartofaccounts(){
  window.location.hash="#output"; 
    $('#accdiv').hide();
   
    $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
    data:{id:10,code:1,user:username},
    success:function(data){
    $('#reportdiv').html(data);
    }
    }); 
}

function ledrep(code){
    var a=1;
    var name = $('#allledger').val();
    var from = $('#from8').val();
    var to = $('#to8').val();
    var view = $('input[name=viewall8]:checked').val();
    if(!(view)){view=0}
    if((from==''||to=='')&&view==0){
    swal("Error", "Enter both Start and End dates!", "error");
    }
    else{
 window.location.hash="#output"; 
    $('#accdiv').hide();
      $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
      $.ajax({
      url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
      data:{id:11,code:2,user:username,d1:from,d2:to,name:name},
      success:function(data){
      $('#reportdiv').html(data);
      }
      }); 
    }
    
}



function finrep(code){
    var a=1;
    var id = $('#fstype').val();
    var from = $('#from9').val();
    var to = $('#to9').val();
    if(to==''){
    swal("Error", "End date is mandatory!", "error");
    }
    else{
 window.location.hash="#output"; 
    $('#accdiv').hide();
      $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
      $.ajax({
      url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
      data:{id:id,code:2,user:username,d1:from,d2:to},
      success:function(data){
      $('#reportdiv').html(data);
      }
      }); 
    }
    
}

function tickaccess(a,b){
   
    setTimeout(function() {

    var param = $('input[name='+a+b+']:checked').val();
    if(param!='YES'){param='NO';}
   
         
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
    data:{id:6,categ:a,code:b,rght:param},
    success:function(data){

    }
    }); 


    }, 1000);


}


myApp.onPageInit('dashboard', function (page)  {

	  window.location.hash="dashboard";  

	loadposts()
	dashdata=window.localStorage.getItem("dashdata");
	var parts=dashdata.split('#',6);
	$('#dash1').html(parts[0]);
	$('#dash2').html(parts[1]);
	$('#dash3').html(parts[2]);
	$('#dash4').html(parts[3]);
	$('#dash5').html(parts[4]);
	$('#dash6').html(parts[5]);

 $("#myUL").html('');
 var array=JSON.parse(window.localStorage.getItem('notifications'));
  for (var i=0;i<array.length;i++){

           xx='';
                
                if(i%2==0){xx='background:#fff';}
                if(array[i]['status']==0){xx='background:#37BC9B';}
                $("#myUL").append("<li><a style='"+xx+"'><span class='spancode'>"+array[i]['sender']+":</span> "+array[i]['message']+"<span class='spanprice'>"+array[i]['date']+"</span></a></li>");
            


  }


	//update notifications 
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
    data:{id:12,user:username},
    success:function(data){
    }
    }); 


	$('#dash1').html();
	var linearr = (new Function("return [" + window.localStorage.getItem("linearr")+ "];")());
  
	var bararr = (new Function("return [" + window.localStorage.getItem("bararr")+ "];")());

	var dougnut = (new Function("return [" + window.localStorage.getItem("dougnut")+ "];")());

	
	

	//line chart
	var chart = new CanvasJS.Chart("chartContainer",
    {
      theme: "theme2",
      title:{
        text: "Sales(Past 10 Days)"
      },
      animationEnabled: true,
     
      axisY:{
        includeZero: false
        
      },
      data: [
      {        
        type: "line",
        //lineThickness: 3,        
        dataPoints: linearr
      }
      
      
      ]
    });

	chart.render();

	
  
	

	
	//bar chart

	var chart2 = new CanvasJS.Chart("chartContainer2",
    {
      title:{
        text: "Expenses(Past 10 Days)"    
      },
      animationEnabled: true,
      axisY: {
        title: "Total(KES)"
      },
      legend: {
        verticalAlign: "bottom",
        horizontalAlign: "center"
      },
      theme: "theme2",
      data: [

      {        
        type: "column",  
        dataPoints: bararr
      }   
      ]
    });

    chart2.render();

    //dougnut

    var chart3 = new CanvasJS.Chart("chartContainer3",
	{
		title:{
			text: "Expense Categories",
			fontFamily: "Impact",
			fontWeight: "normal"
		},

		legend:{
			verticalAlign: "bottom",
			horizontalAlign: "center"
		},
		data: [
		{
			//startAngle: 45,
			indexLabelFontSize: 20,
			indexLabelFontFamily: "Garamond",
			indexLabelFontColor: "darkgrey",
			indexLabelLineColor: "darkgrey",
			indexLabelPlacement: "outside",
			type: "doughnut",
			showInLegend: true,
			dataPoints: dougnut
		}
		]
	});

	chart3.render();
 

})

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [day, month, year].join('/');
}

myApp.onPageInit('features', function (page) {
    window.location.hash="features";  
 $('#regn').focus();

$('.datepicker').focus(function(event) {
    var currentField = $(this);
    var myNewDate = Date.parse(currentField.val()) || new Date();
    myNewDate = new Date (myNewDate);

    // Same handling for iPhone and Android
    window.plugins.datePicker.show({
      date : myNewDate,
      mode : 'date', // date or time or blank for both
      allowOldDates : true
    }, function(returnDate) {
      var newDate = new Date(returnDate);
      currentField.val(formatDate(newDate));

      // This fixes the problem you mention at the bottom of this script with it not working a second/third time around, because it is in focus.
      currentField.blur();
    });
  });

})
myApp.onPageInit('reports', function (page) {
    window.location.hash="reports";  



$('.datepicker').focus(function(event) {
    var currentField = $(this);
    var myNewDate = Date.parse(currentField.val()) || new Date();
    myNewDate = new Date (myNewDate);

    // Same handling for iPhone and Android
    window.plugins.datePicker.show({
      date : myNewDate,
      mode : 'date', // date or time or blank for both
      allowOldDates : true
    }, function(returnDate) {
      var newDate = new Date(returnDate);
      currentField.val(formatDate(newDate));

      // This fixes the problem you mention at the bottom of this script with it not working a second/third time around, because it is in focus.
      currentField.blur();
    });
  });


/*
  $('.nativetimepicker').focus(function(event) {
    var currentField = $(this);
    var time = currentField.val();
    var myNewTime = new Date();

    myNewTime.setHours(time.substr(0, 2));
    myNewTime.setMinutes(time.substr(3, 2));

    // Same handling for iPhone and Android
    plugins.datePicker.show({
      date : myNewTime,
      mode : 'time', // date or time or blank for both
      allowOldDates : true
    }, function(returnDate) {
      // returnDate is generated by .toLocaleString() in Java so it will be relative to the current time zone
      var newDate = new Date(returnDate);
      currentField.val(newDate.toString("HH:mm"));

      currentField.blur();
    });
  });

*/


    $.ajax({
      url:"http://"+window.localStorage.getItem('server')+"/bridge.php?id=16&type=Expense&database="+database,
      data:{},
      success:function(result){
            
            result=JSON.parse(result);
            for (var i=0;i<result.length;i++){

              $("#expenseledger").append('<option value=' + result[i]['ledgerid'] + '>' + result[i]['name'] + '</option>');
            }

      }
      }); 

        var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=17&subcat=Bank&database="+database;
        $.getJSON(url, function(result) {
            console.log(result);
            for (var i=0;i<result.length;i++){

              $("#bankledger").append('<option value=' + result[i]['ledgerid'] + '>' + result[i]['name'] + '</option>');
               }
           
        });


         var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=20&database="+database;
        $.getJSON(url, function(result) {
            console.log(result);
            for (var i=0;i<result.length;i++){

              $("#allledger").append('<option value=' + result[i]['ledgerid'] + '>' + result[i]['name'] + '</option>');
               }
           
        });  
})

function pad(num,size) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
}


function setshopcode(shopitemcode){
	localStorage.setItem("shopitemcode",shopitemcode);
}

function setpolicycode(policycode,mobile){
  localStorage.setItem("shopitemcode",policycode);
  localStorage.setItem("policymobile",mobile);
}


function setitemcode(sysitemcode){
	localStorage.setItem("sysitemcode",sysitemcode);
}
function setcustomercode(syscusno){
  localStorage.setItem("syscusno",syscusno);
}
function setsalecustomercode(salecusno){
  localStorage.setItem("salecusno",salecusno);
}
function setrefundcode(refundcode){
	localStorage.setItem("refundcode",refundcode);
}
function setcontrolcode(controlcode){
	localStorage.setItem("controlcode",controlcode);
}

myApp.onPageInit('policies', function (page){
 window.location.hash="#policies";
 $('#myInput').focus();
 var param = '';
 $("#myUL").html('');
 var array=JSON.parse(window.localStorage.getItem('customers'));


 for (var i=0;i<array.length;i++){

  	 			 xx='';
                 Code=pad(array[i]['cusno'],4);
                 if(i%2==0){xx='background:#fff';}
                  
               	 $("#myUL").append("<li  onclick='setpolicycode(\""+array[i]['cusno']+"\",\""+array[i]['mobile']+"\")'><a style='"+xx+"'  href='policyfunctions.html'><span class='spancode'>#"+Code+"</span> "+array[i]['name']+"<span class='spanprice'>"+array[i]['regn']+"</span></a></li>");

                  
  }
 


})

function calcdiffdays(d1,d2){


        var g=d1.substr(6,2);
        var h=d1.substr(4,2);
        var i=d1.substr(0,4);
        var j=i+h+g;
        var x=h+'/'+ g +'/'+ i;

        var g=d2.substr(6,2);
        var h=d2.substr(4,2);
        var i=d2.substr(0,4);
        var k=i+h+g;
        var y=h+'/'+ g +'/'+ i;
        
        
         //mm/dd/yyyy
         var d11=new Date("" + x + "");
         var d22=new Date("" + y + "");
         var  timeDiff=Math.abs(d22.getTime() - d11.getTime());
         var diff=Math.ceil(timeDiff/(1000*3600*24));

         return diff;


}


myApp.onPageInit('certificates', function (page){
 window.location.hash="#certificates";
 $('#myInput').focus();
 var param = '';
 $("#myUL").html('');
 var array=JSON.parse(window.localStorage.getItem('customers'));


 for (var i=0;i<array.length;i++){

           xx='';
                 Code=pad(array[i]['cusno'],4);
                 if(i%2==0){xx='background:#fff';}
                  
                 $("#myUL").append("<li  onclick='setpolicycode(\""+array[i]['cusno']+"\",\""+array[i]['mobile']+"\")'><a style='"+xx+"'  href='newcert.html'><span class='spancode'>#"+Code+"</span> "+array[i]['name']+"<span class='spanprice'>"+array[i]['regn']+"</span></a></li>");

                  
  }


})

myApp.onPageInit('reminders', function (page){
 window.location.hash="#reminders";
 $('#myInput').focus();
 var param = '';
 $("#myUL").html('');


 var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=28&user="+username+"&database="+database;
  $.getJSON(url, function(array) {
      

      for (var i=0;i<array.length;i++){

           xx='';
                 Code=pad(array[i]['cusno'],4);

                 if(i%2==0){xx='background:#fff';}

                  var d = new Date();
                  month = '' + (d.getMonth() + 1),
                  day = '' + d.getDate(),
                  year = d.getFullYear();

                  if (month.length < 2) month = '0' + month;
                  if (day.length < 2) day = '0' + day;
                  today=[year, month, day].join('');

                  stampexp=array[i]['stampexp'];

                   diff=calcdiffdays(today,stampexp);

                  if(diff>=0&&diff<=7){xx='background:#f00';}
                  if(diff>7&&diff<=14){xx='background:#ff3';}
                  if(diff>14&&diff<=31){xx='background:#0f6';}
                  
                 $("#myUL").append("<li  onclick='setpolicycode(\""+array[i]['cusno']+"\",\""+array[i]['mobile']+"\")'><a style='"+xx+"'  href='newreminder.html'><span class='spancode'>#"+Code+"</span> "+array[i]['name']+"-"+array[i]['regn']+"<span class='spanprice'>"+array[i]['end']+"</span></a></li>");

                  
  }


  });
 
})


myApp.onPageInit('newcert', function (page){
 window.location.hash="#newcert";
   $('.datepicker').focus(function(event) {
    var currentField = $(this);
    var myNewDate = Date.parse(currentField.val()) || new Date();
    myNewDate = new Date (myNewDate);

    // Same handling for iPhone and Android
    window.plugins.datePicker.show({
      date : myNewDate,
      mode : 'date', // date or time or blank for both
      allowOldDates : true
    }, function(returnDate) {
      var newDate = new Date(returnDate);
      currentField.val(formatDate(newDate));
      // This fixes the problem you mention at the bottom of this script with it not working a second/third time around, because it is in focus.
      currentField.blur();
    });
  });
 

  var cusno = window.localStorage.getItem('shopitemcode');
  $.ajax({
  url:'http://'+window.localStorage.getItem('server')+'/bridge.php?database='+database,
  data:{id:12,cusno:cusno},
  success:function(data){
  $('#recdiv').html(data);
   }
  });


})


myApp.onPageInit('refunds', function (page) {
    window.location.hash="refunds";  
 $('#myInput').focus();
 var param = '';
	var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=6&param="+param+"&database="+database;
        $.getJSON(url, function(result) {
            console.log(result);
            $("#myUL").html('');

            $.each(result, function(i, field) {
                
                 xx='';
                 
                 paid=field.AmountPaid.replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');; change=field.Change.replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');; 
                 saleprice=parseFloat(paid,10)-parseFloat(change,10);
				 mode=field.SaleMode; if(mode=='cash'){Code='REC'+pad(field.RcptNo,4);}else{Code='INV'+pad(field.InvNo,4);}
				 date=field.Date;
				 var parts=date.split('/',3);date=parts[2]+'/'+parts[1]+'/'+parts[0]

				if(i%2==0){xx='background:#fff';}
               	$("#myUL").append("<li  onclick='setrefundcode("+field.SaleNo+")'><a style='"+xx+"'  href='creditnote.html'><span class='spancode'>#"+Code+"</span> "+field.ClientName+" "+date+"<span class='spanprice'>"+saleprice+"</span></a></li>");
            });

        

        });	


        
        $("#myInput").keyup(function(e) {
        var param=$('#myInput').val();
        var enterKey = 13;
	        if (e.which == enterKey&&param!=''){
	        		var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=7&rcptno="+param+"&database="+database;
			        $.getJSON(url, function(result) {
			           
			            	$.each(result, function(i, field) {
			            		
			            		saleno=field.SaleNo;

			            	});

			            if(saleno){

			            	setrefundcode(saleno);
			            	$('#tocreditnote a')[0].click();

			            }else{

			            	swal("Error", "No Receipt or Invoice Number matches the Search Parameter!", "error");
			            }
			           
			      
			        }); 
	        }
		});
		



})


myApp.onPageInit('items', function (page) {
    window.location.hash="items";  
 $('#myInput').focus();
 var param = '';
 $("#myUL").html('');
 var array=JSON.parse(window.localStorage.getItem('items'));
  for (var i=0;i<array.length;i++){

  	 			 xx='';
                 Code=pad(array[i]['ItemCode'],4);
                 saleprice=array[i]['SalePrice'];
                 saleprice=parseFloat(saleprice);
				 saleprice=saleprice.formatMoney(2, '.', ',');
				if(i%2==0){xx='background:#fff';}
               	$("#myUL").append("<li  onclick='setitemcode("+array[i]['ItemCode']+")'><a style='"+xx+"'  href='edititem.html'><span class='spancode'>#"+Code+"</span> "+array[i]['ItemName']+"<span class='spanprice'>"+saleprice+"</span></a></li>");
            


  }


})


myApp.onPageInit('policyfunctions', function (page) {
    window.location.hash="policyfunctions";  
    
     var mobile = window.localStorage.getItem('policymobile');
     $('#callcus').attr('href', 'tel:'+mobile);
     $('#smscus').attr('href', 'sms:'+mobile);

})

myApp.onPageInit('newreminder', function (page) {
    window.location.hash="newreminder";  
    
     var mobile = window.localStorage.getItem('policymobile');
     $('#callcus').attr('href', 'tel:'+mobile);
     $('#smscus').attr('href', 'sms:'+mobile);
      $('#phoneno').val(mobile);

})

myApp.onPageInit('customers', function (page) {
window.location.hash="customers";
 $('#myInput').focus();
 var param = '';
 $("#myUL").html('');
 var array=JSON.parse(window.localStorage.getItem('customers'));
  for (var i=0;i<array.length;i++){

           xx='';
                 cusno=pad(array[i]['cusno'],4);
                 names=array[i]['name']+' '+array[i]['oname']+' '+array[i]['lname'];
                 phone=array[i]['phone'];

                if(i%2==0){xx='background:#fff';}
                $("#myUL").append("<li  onclick='setcustomercode("+array[i]['cusno']+")'><a style='"+xx+"'  href='editcustomer.html'><span class='spancode'>#"+cusno+"</span> "+names+"<span class='spanprice'>"+phone+"</span></a></li>");
            


  }


})



myApp.onPageInit('selectcustomer', function (page) {
window.location.hash="selectcustomer";
 $('#myInput').focus();
 var param = '';
 $("#myUL").html('');
 var array=JSON.parse(window.localStorage.getItem('customers'));
  for (var i=0;i<array.length;i++){

                xx='';
                 cusno=pad(array[i]['cusno'],4);
                 names=array[i]['name']+' '+array[i]['oname']+' '+array[i]['lname'];
                 phone=array[i]['phone'];

                if(i%2==0){xx='background:#fff';}
                $("#myUL").append("<li  onclick='setsalecustomercode("+array[i]['cusno']+")'><a style='"+xx+"'  href='checkout.html'><span class='spancode'>#"+cusno+"</span> "+names+"<span class='spanprice'>"+phone+"</span></a></li>");
            


  }


})

myApp.onPageInit('stockcontrol', function (page) {
  window.location.hash="stockcontrol";
 $('#myInput').focus();
 var param = '';
 $("#myUL").html('');
 var array=JSON.parse(window.localStorage.getItem('items'));
  for (var i=0;i<array.length;i++){
  				if(array[i]['Type']==='GOOD'){
  	 			 xx='';
                 Code=pad(array[i]['ItemCode'],4);
                 saleprice=array[i]['SalePrice'];
                 saleprice=parseFloat(saleprice);
				 saleprice=saleprice.formatMoney(2, '.', ',');
				if(i%2==0){xx='background:#fff';}
               	$("#myUL").append("<li  onclick='setcontrolcode("+array[i]['ItemCode']+")'><a style='"+xx+"'  href='addcontrol.html'><span class='spancode'>#"+Code+"</span> "+array[i]['ItemName']+"<span class='spanprice'>"+array[i][database]+"</span></a></li>");
            
               }

  }


})


myApp.onPageInit('addcontrol', function (page) {
	      window.location.hash="addcontrol";    
	    var itemcode = window.localStorage.getItem('controlcode');
			$.ajax({
			url:'http://'+window.localStorage.getItem('server')+'/bridge.php?database='+database,
			data:{id:13,itemcode:itemcode},
			success:function(data){
			$('#recdiv').html(data);
			}
			});
     
})


myApp.onPageInit('expenses', function (page) {
  window.location.hash="expenses";  
$('.datepicker').focus(function(event) {
    var currentField = $(this);
    var myNewDate = Date.parse(currentField.val()) || new Date();
    myNewDate = new Date (myNewDate);

    // Same handling for iPhone and Android
    window.plugins.datePicker.show({
      date : myNewDate,
      mode : 'date', // date or time or blank for both
      allowOldDates : true
    }, function(returnDate) {
      var newDate = new Date(returnDate);
      currentField.val(formatDate(newDate));

      // This fixes the problem you mention at the bottom of this script with it not working a second/third time around, because it is in focus.
      currentField.blur();
    });
  });


      $.ajax({
      url:"http://"+window.localStorage.getItem('server')+"/bridge.php?id=16&type=Expense&database="+database,
      data:{},
      success:function(result){
            
            result=JSON.parse(result);
            for (var i=0;i<result.length;i++){

              $("#ledger").append('<option value=' + result[i]['ledgerid'] + '>' + result[i]['name'] + '</option>');
            }

      }
      }); 

        /*
        var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=16&type=Expense&database="+database;
        $.getJSON(url, function(result) {
            console.log(result);
            for (var i=0;i<result.length;i++){

              $("#ledger").append('<option value=' + result[i]['ledgerid'] + '>' + result[i]['name'] + '</option>');
            }
           
        }); 
      */

})

myApp.onPageInit('bank', function (page) {
   window.location.hash="bank";  
$('.datepicker').focus(function(event) {
    var currentField = $(this);
    var myNewDate = Date.parse(currentField.val()) || new Date();
    myNewDate = new Date (myNewDate);

    // Same handling for iPhone and Android
    window.plugins.datePicker.show({
      date : myNewDate,
      mode : 'date', // date or time or blank for both
      allowOldDates : true
    }, function(returnDate) {
      var newDate = new Date(returnDate);
      currentField.val(formatDate(newDate));

      // This fixes the problem you mention at the bottom of this script with it not working a second/third time around, because it is in focus.
      currentField.blur();
    });
  });
        var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=17&subcat=Bank&database="+database;
        $.getJSON(url, function(result) {
            console.log(result);
            for (var i=0;i<result.length;i++){

              $("#ledger").append('<option value=' + result[i]['ledgerid'] + '>' + result[i]['name'] + '</option>');
               }
           
        }); 

})

myApp.onPageInit('about', function (page) {
   window.location.hash="about";  
})



myApp.onPageInit('settings', function (page) {
    window.location.hash="settings";  
        var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=18&database="+database;
        $.getJSON(url, function(result) {
            console.log(result);
            for (var i=0;i<result.length;i++){
               $("#seluser").append("<option value=\"" + result[i]['userid'] + "#" + result[i]['name'] + "#" + result[i]['fullname'] + "#" + result[i]['position'] + "\">" + result[i]['fullname'] + "</option>");
           
              
            }
           
        });

        $("#myrights").append("<li class=\"table_row\"><div class=\"table_section_14\">Description</div><div class=\"table_section_14\">Admin</div> <div class=\"table_section_14\">Manager</div> <div class=\"table_section_14\">Cashier</div></li>");
        var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=19&database="+database;
        $.getJSON(url, function(result) {
            console.log(result);
           
            for (var i=0;i<result.length;i++){

              checkadmin='';checkmanager='';checkcashier='';
              code=result[i]['AccessCode'];
              if(result[i]['Admin']=='YES'){checkadmin='checked';}
              if(result[i]['Manager']=='YES'){checkmanager='checked';}
              if(result[i]['Cashier']=='YES'){checkcashier='checked';}
                 $("#myrights").append('<li class=\"table_row\"><div class=\"table_section_14\">' + result[i]['Descrip'] + '</div><div class=\"table_section_14\" onclick=\"tickaccess(\'Admin\','+code+')\" ><div class=\"form_row_right\"  style=\"border:0\"><div class=\"item-content\"><div class=\"item-inner\"><div class=\"item-input\"><label class=\"label-switch\" style=\"\"><input type=\"checkbox\" name=\"Admin'+code+'\" '+checkadmin+' value=\"YES\"><div class=\"checkbox\"></div> </label> </div> </div></div></div></div><div class=\"table_section_14\" onclick=\"tickaccess(\'Manager\','+code+')\" ><div class=\"form_row_right\"  style=\"border:0\"><div class=\"item-content\"><div class=\"item-inner\"><div class=\"item-input\"><label class=\"label-switch\" style=\"\"><input type=\"checkbox\" name=\"Manager'+code+'\" '+checkmanager+' value=\"YES\"><div class=\"checkbox\"></div> </label> </div> </div></div></div></div><div class=\"table_section_14\" onclick=\"tickaccess(\'Cashier\','+code+')\"><div class=\"form_row_right\"  style=\"border:0\"><div class=\"item-content\"><div class=\"item-inner\"><div class=\"item-input\"><label class=\"label-switch\" style=\"\"><input type=\"checkbox\"  name=\"Cashier'+code+'\" '+checkcashier+' value=\"YES\"><div class=\"checkbox\"></div> </label> </div> </div></div></div></div></li>');
            
               }
           
        }); 


        //company
       $("#comname").val(window.localStorage.getItem('comname'));
       $("#tel").val(window.localStorage.getItem('comtel'));
       $("#address").val(window.localStorage.getItem('comadd'));
       $("#website").val(window.localStorage.getItem('comweb'));
       $("#email").val(window.localStorage.getItem('comemail'));
       $("#description").val(window.localStorage.getItem('comdesc'));


        var rights = JSON.parse(localStorage.getItem("rights"));
        if(rights[110]=='YES'){ $("#atab2").show()}
        if(rights[111]=='YES'){ $("#atab3").show()}
        if(rights[112]=='YES'){ $("#atab4").show()}
       

 




                                                             

})






myApp.onPageInit('index', function (page) {

   setrightsli();
   window.location.hash="#index";
  $(".logo").animate({'top': '20px'},'slow',"easeInOutCirc");
  $(".cartitems").delay(1000).animate({'width': '30px', 'height': '30px', 'top':'10px', 'right':'10px', 'opacity':1},1000,"easeOutBounce");

  
})

function displaygoodiv(){
type=$('#type').val();
if(type=='GOOD'){$('.goodiv').show();}else{$('.goodiv').hide();}

}

function updateitems(){
		var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=4&database="+database;
        $.getJSON(url, function(result) {
            localStorage.setItem("items", JSON.stringify(result));
        });
}

function updatecustomers(){
    var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=23&database="+database;
        $.getJSON(url, function(result) {
            localStorage.setItem("customers", JSON.stringify(result));
        });
}



function addpolicy(a){
var b = $('#cusno').val();
var names = $('#names').val();
var telno = $('#telno').val();
var idno = $('#idno').val();
var pinno = $('#pinno').val();
var regn = $('#regn').val();
var make = $('#make').val();
var body = $('#body').val();
var vehiclevalue = $('#vehiclevalue').val();
var year = $('#year').val();
var colour = $('#colour').val();
var instype = $('#instype').val();
var inscom = $('#inscom').val();
var policyno = $('#policyno').val();

		if(names==''||telno==''||idno==''||regn==''||instype==''||inscom==''||policyno==''){
		swal("Error", "Fill all the required fields!", "error");
		}
		
		
	else{


       swal({
        title: "Save Policy Info",
        text: "Are you sure you want to save this Customer?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes!",
        closeOnConfirm: true
      },
      function(){

      $("#recdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
        $.ajax({
        url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
        data:{id:17,user:username,a:a,b:b,names:names,telno:telno,idno:idno,pinno:pinno,regn:regn,make:make,body:body,vehiclevalue:vehiclevalue,year:year,colour:colour,instype:instype,inscom:inscom,policyno:policyno},
        success:function(data){
        $('#recdiv').html(data);
        updatecustomers()
        setTimeout(function() {$('#backtopolicies a')[0].click();}, 1000);
        }
        }); 
        
        
      });



		
		}	
}


function addcert(a){
var cusno = $('#cusno').val();
var certno = $('#certno').val();
var tonnage = $('#tonnage').val();
var from = $('#from').val();
var to = $('#to').val();
var certtype = $('#certtype').val();


    if(certno==''||certtype==''||from==''||to==''){
    swal("Error", "Fill all the required fields!", "error");
    }
    
    
  else{


       swal({
        title: "Save Certificate Info",
        text: "Are you sure you want to issue this Certificate?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes!",
        closeOnConfirm: true
      },
      function(){

      $("#recdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
        $.ajax({
        url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
        data:{id:18,user:username,a:a,cusno:cusno,certno:certno,tonnage:tonnage,from:from,to:to,certtype:certtype},
        success:function(data){
        $('#recdiv').html(data);
        setTimeout(function() {$('#backtocertificates a')[0].click();}, 1000);
        }
        }); 
        
        
      });



    
    } 
}


function sendreminder(){
var phoneno = $('#phoneno').val();
var cusno = $('#cusno').val();

  $("#recdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
  $.ajax({
  url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
  data:{id:18,user:username,phoneno:phoneno,cusno:cusno},
  success:function(data){
  $('#recdiv').html(data);
  setTimeout(function() {$('#backtoreminders a')[0].click();}, 1000);
  }
  }); 
        
        
       
}

function edititem(){
var itemcode = $('#itemcode').val();
var itemname = $('#itemname').val();
var type = $('#type').val();
var minbal = $('#minbal').val().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');
var purchprice = $('#purchprice').val().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');
var saleprice = $('#saleprice').val().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');


		if(itemname==''||type==''||saleprice==''){
		swal("Error", "Item Name, Type and Selling Price are mandatory!", "error");
		}
		
		
	else{
		$("#recdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
		$.ajax({
		url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
		data:{id:8,user:username,itemcode:itemcode,itemname:itemname,type:type,purchprice:purchprice,saleprice:saleprice,minbal:minbal},
		success:function(data){
		$('#recdiv').html(data);
		updateitems()
		setTimeout(function() {$('#backtoitems a')[0].click();}, 1000);
		}
		});	
		}	
}

function deleteitem(){
		var itemcode = $('#itemcode').val();

		swal({
		  title: "Delete Item",
		  text: "Are you sure you want to delete this Item?",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		  confirmButtonText: "Yes!",
		  closeOnConfirm: false
		},
		function(){

		$("#recdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
		$.ajax({
		url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
		data:{id:9,user:username,itemcode:itemcode},
		success:function(data){
		$('#recdiv').html(data);
		updateitems()
		setTimeout(function() {$('#backtoitems a')[0].click();}, 1000);
		}
		});	
			
		  
		});

		
		
}


function addcustomer(){
var name = $('#cusname').val();
var phone = $('#cusphone').val();



    if(name==''||phone==''){
    swal("Error", "Customer Name and Phone Number are mandatory!", "error");
    }
    
    
  else{
    $("#recdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
    data:{id:17,user:username,name:name,phone:phone},
    success:function(data){
    $('#recdiv').html(data);
    updatecustomers();
    setTimeout(function() {$('#backtoitems a')[0].click();}, 1000);
    }
    }); 
    } 
}

function editcustomer(){
var name = $('#cusname').val();
var phone = $('#cusphone').val();
var cusno = $('#cusno').val();

    if(name==''||phone==''){
    swal("Error", "Customer Name and Phone Number are mandatory!", "error");
    }
    
    
  else{
    $("#recdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
    data:{id:18,user:username,name:name,phone:phone,cusno:cusno},
    success:function(data){
    $('#recdiv').html(data);
    updatecustomers()
    setTimeout(function() {$('#backtoitems a')[0].click();}, 1000);
    }
    }); 
    } 
}

function deletecustomer(){
      var cusno = window.localStorage.getItem('shopitemcode');
      swal({
      title: "Delete Customer",
      text: "Are you sure you want to delete this Customer?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes!",
      closeOnConfirm: true
    },
    function(){

    $("#recdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
    data:{id:19,user:username,cusno:cusno},
    success:function(data){
    $('#recdiv').html(data);
    updatecustomers()
    setTimeout(function() {$('#backtopolicies a')[0].click();}, 1000);
    }
    }); 
      
      
    });

    
    
}


function deleteuser(){
    var userid = $('#userid2').val();

    swal({
      title: "Delete Item",
      text: "Are you sure you want to delete this User?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes!",
      closeOnConfirm: false
    },
    function(){

    $("#saveuser2").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
    data:{id:15,user:username,userid:userid},
    success:function(data){
    $('#saveuser2').html(data);
    }
    }); 
      
      
    });

    
    
}

function updatebalance(){
var itemcode = $('#itemcode').val();
var itemname = $('#itemname').val();
var description = $('#description').val();
var type = $('#type').val();
var qty = $('#qty').val().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');

		if(itemcode==''||type==''||qty==''){
		swal("Error", "Adjustment Type and Quantity fields are mandatory!", "error");
		}
		
		
	else{
		$("#recdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
		$.ajax({
		url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
		data:{id:11,user:username,itemcode:itemcode,itemname:itemname,type:type,qty:qty,description:description},
		success:function(data){
		$('#recdiv').html(data);
		updateitems()
		setTimeout(function() {$('#backtoitems a')[0].click();}, 1000);
		}
		});	
		}	
}


function sendmessage(){

var name = $('#name').val();
var message = $('#message').val();
var email = $('#email').val();



   if(name==''||email==''||message==''){
    swal("Error", "Fill all the fields!", "error");
	return;
    }
   
   else{

    $('#messagediv').html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
    data:{id:10,name:name,message:message,email:email},
    success:function(data){
    $('#messagediv').html(data);
    }
    }); 
    } 
}

function displayifexists(arr,code){

    for (var i=0;i<arr.length;i++){

    	
       if(arr[i][0]==code){
       $('#quantity').val(arr[i][2]);
       	$('#price').val(arr[i][3]);
       	total=parseFloat(arr[i][4],10);
       	total=total.formatMoney(2, '.', ',');
       	$('#total').val(total);
       }
  }
 
}

function saveexpense(){
var ledger = $('#ledger').val();
var date = $('#date').val();
var description = $('#description').val();
var amount = $('#amount').val().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');


    if(ledger==''||date==''||amount==''||description==''){
    swal("Error", "All the fields are required!", "error");
    }
    
    
  else{
    $("#recdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
    data:{id:13,user:username,ledger:ledger,date:date,description:description,amount:amount},
    success:function(data){
    $('#recdiv').html(data);
    }
    }); 
    } 
}

function savedeposit(){
var ledger = $('#ledger').val();
var date = $('#date').val();
var refno = $('#refno').val();
var description = $('#description').val();
var amount = $('#amount').val().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');


    if(ledger==''||date==''||amount==''||description==''||refno==''){
    swal("Error", "All the fields are required!", "error");
    }
    
    
  else{
    $("#recdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
    data:{id:14,user:username,ledger:ledger,date:date,description:description,amount:amount,refno:refno},
    success:function(data){
    $('#recdiv').html(data);
    }
    }); 
    } 
}


myApp.onPageInit('shopitem', function (page) {
  window.location.hash="#shopitem";
	var array = JSON.parse(localStorage.getItem("cart"));
	 var items = array.length-1;

			$("#cartitemsno2").html(' ['+ items + ']');
         	var itemcode = window.localStorage.getItem('shopitemcode');
			
			$.ajax({
			url:'http://'+window.localStorage.getItem('server')+'/bridge.php?database='+database,
			data:{id:5,itemcode:itemcode},
			success:function(data){
			$('#recdiv').html(data);
			displayifexists(array,itemcode);

			$("#itempic").html("<object data=\"http://"+window.localStorage.getItem('server')+"/images/items/"+itemcode+".jpg\" type=\"image/jpeg\" width=\"300px\"><img src=\"images/items/default.jpg\" /></object>");

			}
			});

			
			

			
       
})


myApp.onPageInit('checkout', function (page) {
  window.location.hash="checkout";  
	var array = JSON.parse(localStorage.getItem("cart"));
	 var tot=0;
	 for (var i=1;i<array.length;i++){

	 	tot+=parseFloat(array[i][4],10);
	 }

    tot=tot.formatMoney(2, '.', ',');
	  $('#totalamount').html(tot);
	  $('#tendered').focus();
     var cusno = window.localStorage.getItem('salecusno');
     $.ajax({
      url:'http://'+window.localStorage.getItem('server')+'/bridge.php?database='+database,
      data:{id:24,cusno:cusno},
      success:function(data){
      $('#recdiv').html(data);
       }
      });
     

			
       
})


myApp.onPageInit('newpolicy', function (page) {
            window.location.hash="newpolicy";



             $.ajax({
              url:"http://"+window.localStorage.getItem('server')+"/bridge.php?id=26&database="+database,
              data:{},
              success:function(result){
                    
                    result=JSON.parse(result);
                    for (var i=0;i<result.length;i++){

                      $("#make").append('<option value=' + result[i]['name'] + '>' + result[i]['name'] + '</option>');
                    }

              }
              });  


              $.ajax({
              url:"http://"+window.localStorage.getItem('server')+"/bridge.php?id=27&database="+database,
              data:{},
              success:function(result){
                    
                    result=JSON.parse(result);
                    for (var i=0;i<result.length;i++){

                      $("#inscom").append('<option value=' + result[i]['name'] + '>' + result[i]['name'] + '</option>');
                    }

              }
              });    


            //$('#make').select2();
           
           
})


myApp.onPageInit('editpolicy', function (page) {
	      window.location.hash="editpolicy";    
            $.ajax({
              url:"http://"+window.localStorage.getItem('server')+"/bridge.php?id=26&database="+database,
              data:{},
              success:function(result){
                    
                    result=JSON.parse(result);
                    for (var i=0;i<result.length;i++){

                      $("#make").append('<option value=' + result[i]['name'] + '>' + result[i]['name'] + '</option>');
                    }

              }
              });  


              $.ajax({
              url:"http://"+window.localStorage.getItem('server')+"/bridge.php?id=27&database="+database,
              data:{},
              success:function(result){
                    
                    result=JSON.parse(result);
                    for (var i=0;i<result.length;i++){

                      $("#inscom").append('<option value=' + result[i]['name'] + '>' + result[i]['name'] + '</option>');
                    }




                    var cusno = window.localStorage.getItem('shopitemcode');
                    $.ajax({
                    url:'http://'+window.localStorage.getItem('server')+'/bridge.php?database='+database,
                    data:{id:12,cusno:cusno},
                    success:function(data){
                    $('#recdiv').html(data);
                     }
                    });

              }
              });    


        	    
     
})


myApp.onPageInit('newcustomer', function (page) {
        window.location.hash="newcustomer";  
      $('#uploadphoto').attr('action', 'http://'+window.localStorage.getItem('server')+'/upload.php?database='+database);
       $.ajax({
      url:'http://'+window.localStorage.getItem('server')+'/bridge.php?database='+database,
      data:{id:25},
      success:function(data){
      $('#recdiv').html(data);
     
      }
      });
     
})


myApp.onPageInit('editcustomer', function (page) {
      window.location.hash="editcustomer";    
      var cusno = window.localStorage.getItem('syscusno');
      //$('#uploadphoto').attr('action', 'http://'+window.localStorage.getItem('server')+'/upload.php?database='+database);
      
      $.ajax({
      url:'http://'+window.localStorage.getItem('server')+'/bridge.php?database='+database,
      data:{id:24,cusno:cusno},
      success:function(data){
      $('#recdiv').html(data);
      $("#leipic").html(" <iframe name=\"leiframe\" id=\"leiframe\" class=\"leiframe\" src=\"http://"+window.localStorage.getItem('server')+"/images/customers/"+cusno+".jpg\"> </iframe>");

      }
      });
     
})

function calcchange(){

var total = $('#totalamount').html().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');
var tendered = $('#tendered').val().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');
var balchange=parseFloat(tendered,10)-parseFloat(total,10);
balchange=balchange.formatMoney(2, '.', ',');
$('#cartchange').html(balchange);
}

function updatecart(fieldName){
	len=fieldName.length-8;
    var pid=fieldName.substr(8, len);
    var qty=$('input[name='+fieldName+']').val();
	var array = JSON.parse(localStorage.getItem("cart"));
	price=parseFloat(array[pid][3],10);
	total=parseFloat((price*qty),10);
	total=total.formatMoney(2, '.', ',');total=total.replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');
	array[pid][2]=qty;
	array[pid][3]=price;
	array[pid][4]=total;
	localStorage.setItem("cart", JSON.stringify(array));
	
	 var tot=0;
	 for (var i=1;i<array.length;i++){

	 	tot+=parseFloat(array[i][4],10);
	 }

	 $('#itemprice'+pid).html(total);
	 tot=tot.formatMoney(2, '.', ',');
  	$('#carttotal').html('KShs. '+ tot);
}


function delitemcart(pid){

	$("#cartitem"+pid).remove();
	var array = JSON.parse(localStorage.getItem("cart"));
	array.splice(pid, 1);
	localStorage.setItem("cart", JSON.stringify(array));
	
	 var tot=0;
	 for (var i=1;i<array.length;i++){

	 	tot+=parseFloat(array[i][4],10);
	 }

	 tot=tot.formatMoney(2, '.', ',');
  	$('#carttotal').html('KShs. '+ tot);
}

myApp.onPageInit('currentcart', function (page) {
	$("#cartdiv").html('');
	  window.location.hash="currentcart";  
	
	 var array = JSON.parse(localStorage.getItem("cart"));
	 var tot=0;
	 for (var i=1;i<array.length;i++){
	 		var num=pad(i,2);
	 		tot+=parseFloat(array[i][4],10);
	 		$("#cartdiv").append("<div class=\"cart_item\" id=\"cartitem"+i+"\"><div class=\"item_title\"><span>"+num+".</span>"+array[i][1]+"</div><div class=\"item_price\" id=\"itemprice"+i+"\">"+array[i][4]+"</div><div class=\"item_thumb\" onclick='setshopcode("+array[i][0]+")'><a href=\"shop-item.html\" class=\"close-panel\"><object data=\"http://"+window.localStorage.getItem('server')+"/images/items/"+array[i][0]+".jpg\"  width=\"100%\" type=\"image/jpeg\"><img src=\"images/items/default.jpg\" /></object></a></div> <div class=\"item_qnty\"><form id=\"myform\" method=\"POST\" action=\"#\"><label>QUANTITY</label> <input type=\"button\" value=\"-\" class=\"qntyminus\" field=\"quantity"+i+"\" style=\"color:#333\" /><input type=\"number\"  onclick=\"setCursor('quantity"+i+"')\"  id=\"quantity"+i+"\"  name=\"quantity"+i+"\" value=\""+array[i][2]+"\" class=\"qnty\"  style=\"color:#333\"  /><input type=\"button\" value=\"+\" class=\"qntyplus\" field=\"quantity"+i+"\"  style=\"color:#333\"  /></form></div><a href=\"#\" class=\"item_delete\" onclick=\"delitemcart("+i+")\"><img src=\"images/icons/blue/trash.png\" alt=\"\" title=\"\" /></a></div>");
            
	}

  tot=tot.formatMoney(2, '.', ',');
  $('#carttotal').html('KShs. '+ tot);

  if(array.length<=1){
  	$("#submitsale").remove()
  }

  $('.qntyplus').click(function(e){
								  
        e.preventDefault();
        var fieldName = $(this).attr('field');
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        if (!isNaN(currentVal)) {
            $('input[name='+fieldName+']').val(currentVal + 1);
        } else {
            $('input[name='+fieldName+']').val(0);
        }

        
        updatecart(fieldName);


		
    });
    $(".qntyminus").click(function(e) {
        e.preventDefault();
        var fieldName = $(this).attr('field');
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        if (!isNaN(currentVal) && currentVal > 0) {
            $('input[name='+fieldName+']').val(currentVal - 1);
        } else {
            $('input[name='+fieldName+']').val(0);
        }

        updatecart(fieldName);
    });

  
       
})

function saveitemcredit(i,code){

	var qty=$('#quantity'+i).val();
	var recqty=$('#recquantity'+i).val();

	if(qty==0){
	swal("Error", "Return Quantity cannot be zero!", "error");
	return;
	}

	else if(parseFloat(qty,10)>parseFloat(recqty,10)){
	swal("Error", "Return Quantity cannot be more than the Quantity sold!", "error");
	return;
	}


	var price=$('#recprice'+i).val();
	var name=$('#itemname'+i).val();
	var transno=$('#transno'+i).val();
	total=parseFloat((price*qty),10);


	var array = JSON.parse(localStorage.getItem("creditnote"));
	for (var x=0;x<array.length;x++){

    
       if(array[x][0]==code){array.splice(x, 1);}
  	
  	}


	newarray = new Array(code, name, qty, price, total, transno);
	array.push(newarray);
		
	localStorage.setItem("creditnote", JSON.stringify(array));

	
	 var tot=0;
	 for (var x=1;x<array.length;x++){

	 	tot+=parseFloat(array[x][4],10);
	 }

	
	 $('#itemprice'+i).html(total);
	 tot=tot.formatMoney(2, '.', ',');
	$('#refundtotal').val(tot);
  	$('#carttotal').html('KShs. '+ tot);

}



function reprintreceipt(){

	window.location.href = "receipt.html";
}
myApp.onPageInit('creditnote', function (page) {
    window.location.hash="creditnote";  
	 		var creditnote = [[]];
	 		localStorage.setItem("creditnote", JSON.stringify(creditnote));
         	var saleno = window.localStorage.getItem('refundcode');
         	tot=0;
         	var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=8&saleno="+saleno+"&database="+database;
        	$.getJSON(url, function(result) {
            console.log(result);
            //reprint receipt
            window.localStorage.setItem('receiptdata',JSON.stringify(result));

            $("#cartdiv").html('');
            
           		 $.each(result, function(i, field) {
                
                 var num=i+1;
                 num=pad(num,2);
			 		
			 		$("#cartdiv").append("<div class=\"cart_item\" id=\"cartitem"+i+"\"><div class=\"item_title\"><span>"+num+".</span>"+field.ItemName+"</div><input type=\"hidden\" id=\"itemname"+i+"\"  value=\""+field.ItemName+"\"/><input type=\"hidden\" id=\"itemcode"+i+"\"  value=\""+field.ItemCode+"\"/><input type=\"hidden\" id=\"recprice"+i+"\"  value=\""+field.UnitPrice+"\"/><input type=\"hidden\" id=\"transno"+i+"\"  value=\""+field.TransNo+"\"/><div class=\"item_price\" id=\"itemprice"+i+"\">0.00</div><div class=\"item_thumb\"><a class=\"close-panel\"><object data=\"http://"+window.localStorage.getItem('server')+"/images/items/"+field.ItemCode+".jpg\"  width=\"100%\" type=\"image/jpeg\"><img src=\"images/items/default.jpg\" /></object></a></div> <div class=\"item_qnty\"><form id=\"myform\" method=\"POST\" action=\"#\"><label>QUANTITY</label> <input type=\"button\" value=\"-\" class=\"qntyminus\" field=\"quantity"+i+"\" style=\"color:#333\" /><input type=\"hidden\" id=\"recquantity"+i+"\"  name=\"recquantity"+i+"\" value=\""+field.Qty+"\"/><input type=\"number\"  onclick=\"setCursor('quantity"+i+"')\"  id=\"quantity"+i+"\"  name=\"quantity"+i+"\" value=\"0\" class=\"qnty\"  style=\"color:#333\"  /><input type=\"button\" value=\"+\" class=\"qntyplus\" field=\"quantity"+i+"\"  style=\"color:#333\"  /></form></div><a href=\"#\" class=\"item_delete\" onclick=\"saveitemcredit("+i+","+field.ItemCode+")\"><img src=\"images/icons/blue/logout.png\" alt=\"\" title=\"\" /></a></div>");
		            

				 });



				 tot=parseFloat(tot,10);
				  tot=tot.formatMoney(2, '.', ',');
				  $('#carttotal').html('KShs. '+ tot);

			  

			       $('.qntyplus').click(function(e){
											  
			        e.preventDefault();
			        var fieldName = $(this).attr('field');
			        var currentVal = parseInt($('input[name='+fieldName+']').val());
			        if (!isNaN(currentVal)) {
			            $('input[name='+fieldName+']').val(currentVal + 1);
			        } else {
			            $('input[name='+fieldName+']').val(0);
			        }

			        
			      //  updatecart(fieldName);


					
			    });
			    $(".qntyminus").click(function(e) {
			        e.preventDefault();
			        var fieldName = $(this).attr('field');
			        var currentVal = parseInt($('input[name='+fieldName+']').val());
			        if (!isNaN(currentVal) && currentVal > 0) {
			            $('input[name='+fieldName+']').val(currentVal - 1);
			        } else {
			            $('input[name='+fieldName+']').val(0);
			        }

			        //updatecart(fieldName);
			    });



        

        	});	

	 


			
       
})



myApp.onPageInit('blogsingle', function (page) {
				  window.location.hash="blogsingle";  						 
			$(".backto").animate({'left': '0px'},'slow',"easeInOutCirc");
			$(".nextto").delay(500).animate({'opacity':1, 'width': '10%',},500,"easeOutBounce");
			$(".post_title_single").animate({'right': '0px'},'slow',"easeInOutCirc");
								   
	
})

function calcitemtot(){

 setTimeout(function() {
var qty = $('#quantity').val();
var price = $('#price').val();
tot=qty*price;
tot=parseFloat(tot);
tot=tot.formatMoney(2, '.', ',');
$('#total').val(tot);
}, 500);
 

}

function multiDimensionalUnique(arr) {
    var uniques = [];
    var itemsFound = {};
    for(var i = 0, l = arr.length; i < l; i++) {
        var stringified = JSON.stringify(arr[i]);
        if(itemsFound[stringified]) { continue; }
        uniques.push(arr[i]);
        itemsFound[stringified] = true;
    }
    return uniques;
}

function removeifexists(arr,code){

    for (var i=0;i<arr.length;i++){

    
       if(arr[i][0]==code){arr.splice(i, 1);}
  }
  return arr;
}




function addtocart(){

var code = $('#itemcode').html();
var name = $('#itemname').html();
var type = $('#itemtype').html();
var bal = $('#itembal').html();
var qty = $('#quantity').val().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');
var price = $('#price').val().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');
var total = $('#total').val().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');
var imei = $('#imei').val();

if(code==''||qty==''||price==''||total==''){
swal("Error", "The Price,Quantity and Total Fields cannot be empty!", "error");
return;
}
else if(total==0){
swal("Error", "Total cannot be zero!", "error");
return;
}

else if((parseFloat(bal,10)<parseFloat(qty,10))&&type=='GOOD'){
swal("Error", "Quantity being sold cannot be more than pending balance!", "error");
return;
}
else{
		
		var array = JSON.parse(localStorage.getItem("cart"));
		array=removeifexists(array,code);
		newarray = new Array(code, name, qty, price, total, imei);
		array.push(newarray);
		//array=multiDimensionalUnique(array);
		localStorage.setItem("cart", JSON.stringify(array));


		swal({
  		title: "Success",
  		text: "Item Added to Cart",
  		timer: 1000,
  		showConfirmButton: false
		});
		//swal("Success!", "Item Added!", "success");
		setTimeout(function() {$('#backtoshop a')[0].click();}, 1000);
		

}


}



myApp.onPageInit('shop', function (page) {
  window.location.hash="shop";  
		$("ul.shop_items > li")
			.css('opacity', '0')
			.each(function(index, item) {
				setTimeout(function() {
					$(item).fadeTo('slow',1,"easeInOutCirc");
				}, index*175);
		});	
			
		$("ul.shop_items > li .shopfav")
			.css('opacity', '0')
			.each(function(index, item) {
				setTimeout(function() {
					$(item).animate({'width':'8%', 'opacity':1},2000,"easeOutBounce");
				}, index*175);
		});	
			
		$("ul.shop_items > li h4")
			.css('opacity', '0')
			.each(function(index, item) {
				setTimeout(function() {
					$(item).animate({'right':'0px', 'opacity':1},800,"easeInOutCirc");
				}, index*175);
		});	
			
		$("ul.shop_items > li div.shop_thumb")
			.css('opacity', '0')
			.each(function(index, item) {
				setTimeout(function() {
					$(item).animate({'left':'0px', 'opacity':1},800,"easeInOutCirc");
				}, index*175);
		});	
			
		$('.qntyplusshop').click(function(e){
									  
			e.preventDefault();
			var fieldName = $(this).attr('field');
			var currentVal = parseInt($('input[name='+fieldName+']').val());
			if (!isNaN(currentVal)) {
				$('input[name='+fieldName+']').val(currentVal + 1);
			} else {
				$('input[name='+fieldName+']').val(0);
			}
			
		});
		$(".qntyminusshop").click(function(e) {
			e.preventDefault();
			var fieldName = $(this).attr('field');
			var currentVal = parseInt($('input[name='+fieldName+']').val());
			if (!isNaN(currentVal) && currentVal > 0) {
				$('input[name='+fieldName+']').val(currentVal - 1);
			} else {
				$('input[name='+fieldName+']').val(0);
			}
		});	
  
})

myApp.onPageInit('shopitem', function (page) {
		  window.location.hash="shopitem";  							   
	$(".shop_item .shop_thumb").animate({'left': '0px', 'opacity':1},'slow',"easeInOutCirc");
	$(".shop_item .shop_item_price").delay(500).animate({'right':'10px', 'opacity':1},500,"easeInOutCirc");
	$(".shop_item a.shopfav").delay(500).animate({'opacity':1, 'width': '10%',},500,"easeOutBounce");
	$(".shop_item a.shopfriend").delay(800).animate({'opacity':1, 'width': '10%',},500,"easeOutBounce");

			
		$('.qntyplusshop').click(function(e){
									  
			e.preventDefault();
			var fieldName = $(this).attr('field');
			var currentVal = parseInt($('input[name='+fieldName+']').val());
			if (!isNaN(currentVal)) {
				$('input[name='+fieldName+']').val(currentVal + 1);
			} else {
				$('input[name='+fieldName+']').val(0);
			}
			
		});
		$(".qntyminusshop").click(function(e) {
			e.preventDefault();
			var fieldName = $(this).attr('field');
			var currentVal = parseInt($('input[name='+fieldName+']').val());
			if (!isNaN(currentVal) && currentVal > 0) {
				$('input[name='+fieldName+']').val(currentVal - 1);
			} else {
				$('input[name='+fieldName+']').val(0);
			}
		});	
  
})


$$(document).on('pageInit', function (e) {
									  
		$("ul.features_list_detailed > li")
			.css('opacity', '0')
			.each(function(index, item) {
				setTimeout(function() {
					$(item).fadeTo('slow',1,"easeInOutCirc");
				}, index*175);
		});	
			
		$("ul.features_list_detailed > li span")
			.css('opacity', '0')
			.each(function(index, item) {
				setTimeout(function() {
					$(item).animate({'bottom':'0px', 'right':'0px', 'opacity':1},800,"easeInOutCirc");
				}, index*175);
		});	
			
		$("ul.features_list_detailed > li h4")
			.css('opacity', '0')
			.each(function(index, item) {
				setTimeout(function() {
					$(item).animate({'right':'0px', 'opacity':1},800,"easeInOutCirc");
				}, index*175);
		});	
			
		$("ul.features_list_detailed > li div.feat_small_icon")
			.css('opacity', '0')
			.each(function(index, item) {
				setTimeout(function() {
					$(item).animate({'left':'0px', 'opacity':1},800,"easeInOutCirc");
				}, index*175);
		});		
		
  		$(".swipebox").swipebox();
		$("#ContactForm").validate({
		submitHandler: function(form) {
		ajaxContact(form);
		return false;
		}
		});
		
		$("#RegisterForm").validate();
		$("#LoginForm").validate();
		$("#ForgotForm").validate();
		
		$('a.backbutton').click(function(){
			parent.history.back();
			return false;
		});
		
        

	$("a.switcher").bind("click", function(e){
		e.preventDefault();
		
		var theid = $(this).attr("id");
		var theproducts = $("ul#photoslist");
		var classNames = $(this).attr('class').split(' ');
		
		
		if($(this).hasClass("active")) {
			// if currently clicked button has the active class
			// then we do nothing!
			return false;
		} else {
			// otherwise we are clicking on the inactive button
			// and in the process of switching views!

  			if(theid == "view13") {
				$(this).addClass("active");
				$("#view11").removeClass("active");
				$("#view11").children("img").attr("src","images/switch_11.png");
				
				$("#view12").removeClass("active");
				$("#view12").children("img").attr("src","images/switch_12.png");
			
				var theimg = $(this).children("img");
				theimg.attr("src","images/switch_13_active.png");
			
				// remove the list class and change to grid
				theproducts.removeClass("photo_gallery_11");
				theproducts.removeClass("photo_gallery_12");
				theproducts.addClass("photo_gallery_13");

			}
			
			else if(theid == "view12") {
				$(this).addClass("active");
				$("#view11").removeClass("active");
				$("#view11").children("img").attr("src","images/switch_11.png");
				
				$("#view13").removeClass("active");
				$("#view13").children("img").attr("src","images/switch_13.png");
			
				var theimg = $(this).children("img");
				theimg.attr("src","images/switch_12_active.png");
			
				// remove the list class and change to grid
				theproducts.removeClass("photo_gallery_11");
				theproducts.removeClass("photo_gallery_13");
				theproducts.addClass("photo_gallery_12");

			} 
			else if(theid == "view11") {
				$("#view12").removeClass("active");
				$("#view12").children("img").attr("src","images/switch_12.png");
				
				$("#view13").removeClass("active");
				$("#view13").children("img").attr("src","images/switch_13.png");
			
				var theimg = $(this).children("img");
				theimg.attr("src","images/switch_11_active.png");
			
				// remove the list class and change to grid
				theproducts.removeClass("photo_gallery_12");
				theproducts.removeClass("photo_gallery_13");
				theproducts.addClass("photo_gallery_11");

			} 
			
		}

	});	
	
	document.addEventListener('touchmove', function(event) {
	   if(event.target.parentNode.className.indexOf('navbarpages') != -1 || event.target.className.indexOf('navbarpages') != -1 ) {
		event.preventDefault(); }
	}, false);
	
	// Add ScrollFix
	var scrollingContent = document.getElementById("pages_maincontent");
	new ScrollFix(scrollingContent);
	
	
	var ScrollFix = function(elem) {
		// Variables to track inputs
		var startY = startTopScroll = deltaY = undefined,
	
		elem = elem || elem.querySelector(elem);
	
		// If there is no element, then do nothing	
		if(!elem)
			return;
	
		// Handle the start of interactions
		elem.addEventListener('touchstart', function(event){
			startY = event.touches[0].pageY;
			startTopScroll = elem.scrollTop;
	
			if(startTopScroll <= 0)
				elem.scrollTop = 1;
	
			if(startTopScroll + elem.offsetHeight >= elem.scrollHeight)
				elem.scrollTop = elem.scrollHeight - elem.offsetHeight - 1;
		}, false);
	};
	
		
		
})


//CUSTOM CODE

function logout(){

		swal({
		  title: "Logout Confirmation",
		  text: "Are you sure you want to logout?",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		  confirmButtonText: "Logout!",
		  closeOnConfirm: false
		},
		function(){

			window.location.href = "index.html";
		  
		});

	
}

function submitrefund(){
   var saleno=localStorage.getItem("refundcode");
   var total = $('#refundtotal').val().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');
   var creditnote = localStorage.getItem("creditnote");
   var username = window.localStorage.getItem('ls_userid');
	if(total==''||total==0){
		swal("Error", "No amount to be refunded!", "error");
		return;
	}else{

		swal({
		  title: "Post Refund",
		  text: "Are you sure you want to post the Refund?",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		  confirmButtonText: "Yes!",
		  closeOnConfirm: false
		},
		function(){

			$("#recdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');

			
			$.ajax({
			url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
			data:{id:2,fintot:total,saleno:saleno,creditnote:creditnote,user:username},
			success:function(data){
			setTimeout(function() {window.location.href = "main.html";}, 1000);
			$('#recdiv').html(data);
			}
			});
			
		  
		});


	}
}


function postreceipt(){
	var name=$('#cusname').val();
	var phone=$('#cusphone').val();
  var cusno=$('#cusno').val();
	var refno=$('#refno').val();
	var total = $('#totalamount').html().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');
	var changeam = $('#cartchange').html().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');
    var tendered = $('#tendered').val().replace(/[&\/\\#,+()$~%'":*?<>{}]/g,'');
    var paymode = $('input[name=paymode]:checked').val();
    var cart = localStorage.getItem("cart");
    var username = window.localStorage.getItem('ls_userid');
	
	if(tendered==''||tendered==0){
		swal("Error", "No amount tendered!", "error");
	}
	else if(paymode==''){
		swal("Error", "No Payment Method checked!", "error");
	}
	else if((name==''||phone=='')&&paymode=='628'){
		swal("Error", "Name and Phone number are mandatory if payment mode is Credit!", "error");
	}
	else if(parseFloat(changeam,10)<0){
		swal("Error", "The Change cannot be a negative amount!", "error");
	}
	else{

		
		swal({
		  title: "Post Payment",
		  text: "Are you sure you want to post the payment?",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		  confirmButtonText: "Yes!",
		  closeOnConfirm: false
		},
		function(){

			$("#recdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');

			
			$.ajax({
			url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
			data:{id:1,name:name,user:username,phone:phone,fintot:total,tendered:tendered,changeam:changeam,paymode:paymode,cart:cart,refno:refno,cusno:cusno},
			success:function(data){
			//data=JSON.parse(data);
			//window.location.href = "ticket.html";
			$('#recdiv').html(data);
      localStorage.setItem("salecusno",'');
			}
			});
			
		  
		});


	

	}


	

}

function searchtickets(e){
		var param = $('#searchtickets').val();
		var enterKey = 13;
        if (e.which == enterKey&&param!=''){
        	$(".post_entry").html('');
        	var url = "http://"+window.localStorage.getItem('server')+"/bridge.php?id=5&param="+param+"&database="+database;
        	$.getJSON(url, function(result) {
            console.log(result);
            $.each(result, function(i, field) {
                var regn = field.regn;
               	$(".post_entry").append("<a href='payments2.html?id=3&ticketno=" + field.ticketno + "'><div class='post_date' style='cursor:pointer'><span class='day'>" + field.regn + "</span><span class='month' style='color:#333'>" + field.checkindate + field.checkintime + "</span></div></a>");
            });

            loadposts()
            $('#searchtickets').focus();
           });
           
		}
}


function savepass(){
	
	var opass=$('#opass').val();
	var npass=$('#npass').val();
	var cpass=$('#cpass').val();

	if(opass==''){
	swal("Error", "Enter your old password!", "error");
	}
	else if(npass==''){
	swal("Error", "Enter a new password!", "error");
	}
	else if(cpass==''){
	swal("Error", "Confirm password!", "error");
	}
	else{
	$("#savepass").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
	$.ajax({
	url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
	data:{id:3,opass:opass,npass:npass,cpass:cpass,user:username},
	success:function(data){
	$('#savepass').html(data);
	}
	});
	}
}

function addnewuser(){
var user = $('#username').val();
var name = $('#userfullname').val();
var pass = $('#userpass').val();
var pos = $('#userpos').val();
		if(user==''){
		swal("Error", "Enter the User name!", "error");
		}
		else if(name==''){
		swal("Error", "Enter the full names!", "error");
		}
		else if(pass==''){
		swal("Error", "Enter a valid Password!", "error");
		}
		else if(pos==''){
		swal("Error", "Select the user position!", "error");
		}
		
		
	else{
		$("#saveuser").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
		$.ajax({
		url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
		data:{id:4,user:user,name:name,pos:pos,pass:pass,username:username},
		success:function(data){
		$('#saveuser').html(data);
		}
		});	
		}	
}


function setpass(){
	 var str = $('#seluser').val();
	 var parts=str.split('#',4);
	 $('#userid2').val(parts[0]);
	 $('#username2').val(parts[1]);
	 $('#userfullname2').val(parts[2]);
	 $('#userpos2').val(parts[3]);
}


function edituser(){
var userid = $('#userid2').val();
var user = $('#username2').val();
var name = $('#userfullname2').val();
var pos = $('#userpos2').val();
var respass = $('input[name=respass]:checked').val();
if(respass!=1){respass=0}

		if(userid==''){
		swal("Error", "Select the User name!", "error");
		}
		else if(user==''){
		swal("Error", "Enter the User name!", "error");
		}
		else if(name==''){
		swal("Error", "Enter the full names!", "error");
		}
		else if(pos==''){
		swal("Error", "Select the user position!", "error");
		}
		
		
	else{
		$("#saveuser2").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
		$.ajax({
		url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
		data:{id:5,user:user,name:name,pos:pos,userid:userid,respass:respass,username:username},
		success:function(data){
		$('#saveuser2').html(data);
		}
		});	
		}	
}


function savecompany(){
var comname = $('#comname').val();
var tel = $('#tel').val();
var address = $('#address').val();
var website = $('#website').val();
var email = $('#email').val();
var location = $('#description').val();


    if(tel==''||address==''){
    swal("Error", "Telephone and address fields are mandatory!", "error");
    }
   
    
    
  else{
    $("#savecom").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
    $.ajax({
    url:'http://'+window.localStorage.getItem('server')+'/data.php?database='+database,
    data:{id:16,user:username,comname:comname,tel:tel,address:address,website:website,email:email,location:location},
    success:function(data){
    $('#savecom').html(data);
    }
    }); 
    } 
}



function audittrail(){
		var a=6;code=0;
		var from = $('#dfrom').val();
		var to = $('#dto').val();
		var view = $('input[name=dviewall]:checked').val();
		if(!(view)){view=0}
		if((from==''||to=='')&&view==0){
		swal("Error", "Enter both Start and End dates!", "error");
		}
		
		else{
		   window.location.hash="#output"; 
    $('#accdiv').hide();  
       $("#reportdiv").html('<img id="img-spinner" src="images/load.gif" style="" alt="Loading"/>');
      $.ajax({
      url:'http://'+window.localStorage.getItem('server')+'/report.php?database='+database,
      data:{id:6,code:1,user:username,d1:from,d2:to},
      success:function(data){
      $('#reportdiv').html(data);
      }
      }); 
		}
}


function myFunction() {
    // Declare variables
    var input, filter, ul, li, a, i;
    input = document.getElementById('myInput');
    filter = input.value.toUpperCase();
    ul = document.getElementById("myUL");
    li = ul.getElementsByTagName('li');

    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}





window.addEventListener('popstate', function(event) {
    // The popstate event is fired each time when the current history entry changes.

       event.preventDefault();
        if(window.location.href.indexOf('#policies') >= 0){
          $('#backtopolicies a')[0].click();
        }
        else if(window.location.href.indexOf('#policyfunctions') >= 0){
          $('#backtopolicyfunctions a')[0].click();
        }


        else if(window.location.href.indexOf('#certificates') >= 0){
          $('#backtocertificates a')[0].click();
        }

        else if(window.location.href.indexOf('#reminders') >= 0){
          $('#backtoreminders a')[0].click();
        }

        else if(window.location.href.indexOf('#currentcart') >= 0){
          $('#backtocart a')[0].click();
        }
        else if(window.location.href.indexOf('#customers') >= 0){
          $('#backtocus a')[0].click();
        }
        else if(window.location.href.indexOf('#refunds') >= 0){
          $('#backtorefunds a')[0].click();
        }
        else if(window.location.href.indexOf('#items') >= 0){
          $('#backtoitems a')[0].click();
        }
        else if(window.location.href.indexOf('#stockcontrol') >= 0){
          $('#backtocontrol a')[0].click();
        }
         else if(window.location.href.indexOf('#reports') >= 0){
          $('#reportdiv').html('');$('#accdiv').show();
        }
       
       
       
        

}, false);





