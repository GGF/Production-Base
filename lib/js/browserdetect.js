	
	var cmsBrowser = {
		
		nav:			window.navigator,
		name:			"Unknown",
		version:	"UnknownVersion",
		mod:			"UnknownVersion",
		os:				{name: "UnknownOS", version: "UnknownOS"},
		
		ie				: false,
		ie6				: false,
		firefox		: false,
		opera			: false,
		safari		: false,
		chrome		: false,
		webkit		: false,
		konqueror	: false,
		
		getOS: function() {
			
			if (this.nav.userAgent.indexOf("Windows NT 5") != -1) return "XP";
			else if (this.nav.userAgent.indexOf("Windows NT 6") != -1) return "Vista";
			else return "";
			
		},
		
		init: function() {
			
			if (window.opera) {
				
				this.name			= "Opera";
				this.version	= (this.nav.userAgent) ? this.version = /Opera.([\d.]+)/i.exec(this.nav.userAgent)[1] : "";
				this.mod			= (this.version >= 9.5) ? 95 : (this.version >= 9 ? 9 : 8);
				
			}
			
			// IE
			
			if (
				(this.nav.cpuClass) || (
				(this.nav.appName.indexOf("Explorer") != -1 || this.nav.appName.indexOf("Microsoft") != -1) &&
				((this.nav.appVersion.indexOf("MSIE") != -1 && this.nav.userAgent.indexOf("MSIE") != -1) || (this.nav.appVersion.indexOf(".NET") != -1 && this.nav.userAgent.indexOf(".NET") != -1))
			)) {
				
				this.name			= "Explorer";
				this.version	= (this.nav.appVersion) ? (/MSIE ([\d.]+)/i.test(this.nav.appVersion) ? RegExp.$1 : /MSIE ([\d.]+)/i.exec(this.nav.userAgent)[1]) : "";
				this.mod			= Math.floor(this.version);
				
			}
			
			// Konqueror
			
			if (this.nav.vendor && this.nav.vendor.indexOf("KDE") != -1) {
				
				this.name			= "Konqueror";
				this.version	= (this.nav.appVersion) ? /Konqueror\/([\d.]+)/i.exec(this.nav.appVersion)[1] : "";
				this.mod			= Math.floor(this.version);
				
			}
			
			// Safari
			
			if (this.nav.vendor && this.nav.vendor.indexOf("Apple") != -1) {
				
				this.name			= "Safari";
				this.version	= (this.nav.appVersion) ? /Version\/([\d.]+)/i.exec(this.nav.appVersion)[1] : "";
				this.mod			= Math.floor(this.version);
				
			}
			
			// Chrome
			
			if (this.nav.vendor && this.nav.vendor.indexOf("Google") != -1) {
				
				this.name			= "Chrome";
				this.version	= (this.nav.appVersion) ? /Chrome\/([\d.]+)/i.exec(this.nav.appVersion)[1] : "";
				this.mod			= Math.floor(this.version);
				
			}
			
			// Firefox
			
			if (
				(this.nav.mozIsLocallyAvailable) || (
				(this.nav.appName && this.nav.appName == "Netscape") && 
				(this.nav.product && this.nav.product == "Gecko") && 
				(this.nav.userAgent.indexOf("Firefox") != -1)
			)) {
				
				this.name			= "Firefox";
				this.version	= (this.nav.userAgent) ? /Firefox\/([\d]+\.[\d]+)/i.exec(this.nav.userAgent)[1] : "";
				this.mod			= Math.floor(this.version);
				
			}
			
			this.os.name = this.nav.platform.substr(0, 3);
			this.os.version = this.getOS();
			
			document.getElementsByTagName("HTML")[0].className += " " + [
				this.name.toLowerCase(),
				this.name.toLowerCase() + this.mod,
				this.os.name.toLowerCase(),
				this.os.name.toLowerCase() + this.os.version
			].join(" ");
			
			if (this.name == "Explorer")	{ this.ie = true; if (this.version <= 6) this.ie6 = true; }
			if (this.name == "Firefox")		{ this.firefox = true; }
			if (this.name == "Opera")			{ this.opera = true; }
			if (this.name == "Safari")		{ this.safari = true; this.webkit = true; }
			if (this.name == "Chrome")		{ this.chrome = true; this.webkit = true; }
			if (this.name == "Konqueror")	{ this.konqueror = true; }
			
			return this;
			
		}
		
	}.init();
	
	//alert(document.getElementsByTagName("HTML")[0].className);
