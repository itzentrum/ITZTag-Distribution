if(!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(needle) {
        for(var i = 0; i < this.length; i++) {
            if(this[i] === needle) {
                return i;
            }
        }
        return -1;
    };
}

Array.prototype.contains = function(obj) {
    var i = this.length;
    while (i--) {
        if (this[i] == obj) {
            return true;
        }
    }
    return false;
}

function balken(nod,tabl,tags)
{
    console.log(nod);
    console.log(tabl);
    while(tabl.hasChildNodes())
    {
	tabl.removeChild(tabl.childNodes[0]);
    }
    /* Zustand des Parsers 
       0 - Kein Text
       1 - Einfacher Text
       2 - Getaggter Text
    */
    var Zustand=1;
    var AktTag=""; // Aktueller Tag falls im Zustand 2
    var Elemente=new Array();
    var Tagliste=new Object();
    var Balkendaten=new Array();
    var Balkenposition=-1;
    var Balkenlaenge=0;
    for (var ct=0;ct<tags.length;ct++)
    {
	Tagliste[tags[ct].label.toLowerCase()]=tags[ct].color;
    }
//    console.log(Tagliste);
    for(var ct=nod.childNodes.length-1;ct>=0;ct--)
    {
	Elemente.unshift(nod.childNodes[ct]);
    }
    while(Elemente.length>0)
    {
	//console.log(Elemente);
	var E=Elemente.shift();
	// Text Node
	if(E.nodeType==Node.TEXT_NODE)
	{
	    var Text=E.nodeValue.replace(/\s+/g," ").replace(/^\s/g,"").replace(/\s$/,"");
	    if (Text!="")
	    {
/*
		var Laenge=Text.split(" ").length;
		var NZeile=document.createElement("tr");
		var NZelle=document.createElement("td");
		NZelle.height=Laenge*2+"px";
		NZelle.title=Text;
		NZelle.width="15px";
		if (Zustand==1)
		{
		    NZelle.style.background="white";
		}
		else if (Zustand==2)
		{
		    console.log("Bunt");
		    NZelle.style.background=Tagliste[AktTag];
		    //Zustand=0;
		}
		NZeile.appendChild(NZelle);
		tabl.appendChild(NZeile);
*/
		var Laenge=Text.split(" ").length;
		Balkenlaenge+=Laenge;
		if (Zustand==1)
		{
		    // Ist das gleiche wie davor
		    if (Balkenposition>-1 && Balkendaten[Balkenposition]["color"]=="white")
		    {
//			console.log("mehr1");
			Balkendaten[Balkenposition]["text"]+=" "+Text;
		    }
		    // Neu hinzufuegen
		    else
		    {
			var Balkendatum=new Object();
			Balkendatum["color"]="white";
			Balkendatum["text"]=Text;
			Balkendaten.push(Balkendatum);
			Balkenposition++;
		    }
		}
		else if (Zustand==2)
		{
		    // Wieder das gleiche
		    if (Balkenposition>-1 && Balkendaten[Balkenposition]["color"]==Tagliste[AktTag])
		    {
//			console.log("mehr2");
			Balkendaten[Balkenposition]["text"]+=" "+Text;
		    }
		    // Neu hinzufuegen
		    else
		    {
			var Balkendatum=new Object();
			Balkendatum["color"]=Tagliste[AktTag];
			Balkendatum["text"]=Text;
			Balkendaten.push(Balkendatum);
			Balkenposition++;
		    }
		}
	    }
	}
	// Tag Node
	else (E.nodeType==Node.ELEMENT_NODE)
	{
	    if (Object.keys(Tagliste).contains(E.nodeName.toLowerCase()))
	    {
//		console.log("Fund "+E.nodeName.toLowerCase());
		Zustand=2;
		AktTag=E.nodeName.toLowerCase();
	    }
	    else if (E.nodeName.toLowerCase().search(/_end/)!=-1)
	    {
//		console.log("Ende "+E.nodeName.toLowerCase());
		Zustand=1;
	    }
	}
	if(E.hasChildNodes())
	{
	    if (Object.keys(Tagliste).contains(E.nodeName.toLowerCase()))
	    {
		    Elemente.unshift(document.createElement(E.nodeName.toLowerCase()+"_end"));
	    }
	    for(var ct=E.childNodes.length-1;ct>=0;ct--)
	    {
		    Elemente.unshift(E.childNodes[ct]);
	    }
	}
    }
    for (var i=0; i<Balkendaten.length; i++)
    {
	var Laenge=Balkendaten[i]["text"].split(" ").length;
	var NZeile=document.createElement("tr");
	var NZelle=document.createElement("td");
	NZelle.height=Math.round(Laenge*(800/Balkenlaenge))+"px";
	NZelle.title=Balkendaten[i]["text"];
	NZelle.width="15px";
	NZelle.style.background=Balkendaten[i]["color"];
	NZeile.appendChild(NZelle);
	tabl.appendChild(NZeile);
    }
    /*for (var i=0; i<10; i++)
    {
	var E=document.createElement("td");
	E.width="10px";
	if (i%2==0)
	{
	    E.style.background="black";
	}
	else
	{
	    E.style.background="white";
	}
	tabl.appendChild(E);
    }*/
}