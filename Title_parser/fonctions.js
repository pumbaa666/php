<script language="Javascript">
<!--
//Applies only to IE 4+
//0=no, 1=yes
var copytoclip=1;

/**
 * Sélectionne tout le texte d'un objet
 */
function highlightAll(theField)
{
	var tempval=eval("document."+theField);
	tempval.select();
	copyToClipboard(tempval.value);
	if(document.all&&copytoclip==1)
	{
		therange=tempval.createTextRange();
		therange.execCommand("Copy");
		window.status="Contents highlighted and copied to clipboard!";
		setTimeout("window.status=''",1800);
	}
}

/**
 * Copie du texte dans le presse papier
 */
function copyToClipboard(text)
{
	if(window.clipboardData)
		window.clipboardData.setData("Text", text); // the IE-manier
	else if (window.netscape) 
	{
		netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
		var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
		if(!clip)
			return;

		var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
		if(!trans)
			return;
		trans.addDataFlavor('text/unicode');

		var str = new Object();
		var len = new Object();
		var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		var copytext=text;

		str.data=copytext;
		trans.setTransferData("text/unicode",str,copytext.length*2);

		var clipid=Components.interfaces.nsIClipboard;

		if(!clip)
			return false;

		clip.setData(trans,null,clipid.kGlobalClipboard);
	}
	return false;
}

//-->
</script>
