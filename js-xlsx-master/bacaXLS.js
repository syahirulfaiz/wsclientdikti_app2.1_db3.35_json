
var X = XLSX;
var XW = {
	/* worker message */
	msg: 'xlsx',
	/* worker scripts */
	/*VERSI PATH ASLI
	rABS: './xlsxworker2.js',
	norABS: './xlsxworker1.js',
	noxfer: './xlsxworker.js'
	*/
	rABS: '../js-xlsx-master/xlsxworker2.js',
	norABS: '../js-xlsx-master/xlsxworker1.js',
	noxfer: '../js-xlsx-master/xlsxworker.js'
};

var rABS = typeof FileReader !== "undefined" && typeof FileReader.prototype !== "undefined" && typeof FileReader.prototype.readAsBinaryString !== "undefined";
var use_worker = typeof Worker !== 'undefined';
var transferable = use_worker;
var wtf_mode = false;

function fixdata(data) {
	var o = "", l = 0, w = 10240;
	for(; l<data.byteLength/w; ++l) o+=String.fromCharCode.apply(null,new Uint8Array(data.slice(l*w,l*w+w)));
	o+=String.fromCharCode.apply(null, new Uint8Array(data.slice(l*w)));
	return o;
}

function ab2str(data) {
	var o = "", l = 0, w = 10240;
	for(; l<data.byteLength/w; ++l) o+=String.fromCharCode.apply(null,new Uint16Array(data.slice(l*w,l*w+w)));
	o+=String.fromCharCode.apply(null, new Uint16Array(data.slice(l*w)));
	return o;
}

function s2ab(s) {
	var b = new ArrayBuffer(s.length*2), v = new Uint16Array(b);
	for (var i=0; i != s.length; ++i) v[i] = s.charCodeAt(i);
	return [v, b];
}

function xw_noxfer(data, cb) {
	var worker = new Worker(XW.noxfer);
	worker.onmessage = function(e) {
		switch(e.data.t) {
			case 'ready': break;
			case 'e': console.error(e.data.d); break;
			case XW.msg: cb(JSON.parse(e.data.d)); break;
		}
	};
	var arr = rABS ? data : btoa(fixdata(data));
	worker.postMessage({d:arr,b:rABS});
}

function xw(data, cb) {
	xw_noxfer(data, cb);
}


function to_json(workbook) {
	var result = {};
	workbook.SheetNames.forEach(function(sheetName) {
		var roa = X.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
		if(roa.length > 0){
			result[sheetName] = roa; 
		}
	});
	
	return result;
}




function process_wb(wb) {
	var output = "";
	output = JSON.stringify(to_json(wb), 2, 2);
	
	/* ASLI, diganti innerHTML
	if(out.innerText === undefined) out.textContent = output;
	else out.innerText = output;
	if(typeof console !== 'undefined') console.log("output", new Date());
	*/
	
	if(out.innerHTML === undefined) out.textContent = output;
	else out.innerHTML = output;
	if(typeof console !== 'undefined') console.log("output", new Date());
	
	document.getElementById('json_temp').value = output ;
	//document.getElementById('json_temp').value = "DARI bacaXLS.js" ;
}



var xlf = document.getElementById('xlf');
function handleFile(e) {
	
	rABS = true;
	use_worker = true;
	
	var files = e.target.files;
	var f = files[0];
	{
		var reader = new FileReader();
		var name = f.name;
		reader.onload = function(e) {
			if(typeof console !== 'undefined') console.log("onload", new Date(), rABS, use_worker);
			var data = e.target.result;
			if(use_worker) {
				xw(data, process_wb);
			} else {
				var wb;
				if(rABS) {
					wb = X.read(data, {type: 'binary'});
				} else {
				var arr = fixdata(data);
					wb = X.read(btoa(arr), {type: 'base64'});
				}
				process_wb(wb);
			}
		};
		if(rABS) reader.readAsBinaryString(f);
		else reader.readAsArrayBuffer(f);
	}
}

if(xlf.addEventListener) xlf.addEventListener('change', handleFile, false);

