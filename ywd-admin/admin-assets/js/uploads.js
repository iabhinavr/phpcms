var form = document.forms.namedItem("pageform");
form.addEventListener('submit', function(ev) {

var oOutput = document.querySelector(".msg"),
oData = new FormData(form);
oData.append("username","hello");

var pc = document.querySelector(".percent");
var oReq = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
var start_time = new Date();
var pg = document.getElementById("progressvalue");

oReq.onloadstart = function(oEvent) {
	pg.style.width = 0;
};
	
oReq.upload.onprogress = function (oEvent) {
	console.log("Processing Upload");
	if (oEvent.lengthComputable) {
		var percentComplete = Math.round((oEvent.loaded / oEvent.total) * 100);
		pg.style.width = percentComplete + '%';
		pg.style.animation = 'move .7s linear infinite';
		$("#progressvalue").css("transition", "all .5s ease-out");
		$("#progressvalue").css("background-image", "linear-gradient( -45deg, #e27500 25%, #cf6c03 25%, #cf6c03 50%, #e27500 50%, #e27500 75%, #cf6c03 75%, #cf6c03)");
		var seconds_elapsed = (new Date().getTime() - start_time.getTime())/1000;
		var speed_kbps = Math.round(seconds_elapsed ? ((oEvent.loaded/1000)/seconds_elapsed)*8 : 0);
		var speed_disp = speed_kbps < 1000 ? speed_kbps + ' Kbps' : (speed_kbps/1000).toFixed(2) + ' Mbps';
		pg.style.height = '20px';
		pc.innerHTML = (oEvent.loaded/1000000).toFixed(2) + " / " + (oEvent.total/1000000).toFixed(2) + " MB at " + speed_disp;
	} else {
			// Unable to compute progress information since the total size is unknown
	}
};

  oReq.onload = function(oEvent) {
    if (oReq.status == 200) {
		var resp = JSON.parse(oReq.responseText);
      	pg.style.animation = 'none';
		$("#progressvalue").css("transition", "none");
		if(resp.st == false) {
			$("#progressvalue").css("background-image", "linear-gradient( -45deg, #cc0000 25%, #aa0000 25%, #aa0000 50%, #cc0000 50%, #cc0000 75%, #aa0000 75%, #aa0000)");
			oOutput.innerHTML = "<p class='alert alert-danger'>" + resp.msg + "</p>";
			pc.innerHTML = "Error! Please check again";
		}
		else {
			$("#progressvalue").css("background-image", "linear-gradient( -45deg, #00cc40 25%, #00aa30 25%, #00aa30 50%, #00cc40 50%, #00cc40 75%, #00aa30 75%, #00aa30)");
			oOutput.innerHTML = "<p class='alert alert-success'>" + resp.msg + "</p>";
			document.getElementById("page-list").innerHTML = resp.out;
		}
    } 
	 else {
      	oOutput.innerHTML = "Error " + oReq.status + " occurred when trying to upload your file.<br \/>";
    }
  };
  oReq.open("POST", "uploads.php", true);
  oReq.send(oData);
  ev.preventDefault();
}, false);