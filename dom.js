function print_dom(nod)
{
    TreeView=window.open("about:blank","Dom Tree","height=300,width=400,scrollbars=yes",false);
    TreeView.document.write("<h1>Dom Tree</h1>");
    //print_dom_rec(nod,TreeView.document,"");
    print_html_rec(nod,TreeView.document,"");
}
function print_dom_rec(nod,doc,space)
{
    doc.write(space+nod.nodeName+"<br />");
    for(var ct=0; ct<nod.childNodes.length;ct++)
    {
	print_dom_rec(nod.childNodes[ct],doc,space+"&nbsp;&nbsp;");
    }
}
function print_html_rec(nod,doc,space)
{
    if (nod.nodeName!="#text")
    {
	doc.write(space+"&lt;"+nod.nodeName+"&gt;<br />");
	for(var ct=0; ct<nod.childNodes.length;ct++)
	{
	    print_html_rec(nod.childNodes[ct],doc,space+"&nbsp;&nbsp;");
	}
	if (nod.nodeName!="#text")
	    doc.write(space+"&lt;/"+nod.nodeName+"&gt;<br />");
    }
    else
    {
	doc.write(space+nod.nodeValue.replace(/</g,"&lt;").replace(/\n/g,"<br />")+"<br />");
    }
}