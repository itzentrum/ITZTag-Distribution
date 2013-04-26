define("dijit/_editor/plugins/ITZTag", [
	"dojo/_base/declare", // declare
	"dojo/_base/lang", // lang.hitch
	"../_Plugin",
        "../../form/Button",
        "dojo/keys"
], function(declare, lang, _Plugin, Button){

var ITZTag = declare("dijit._editor.plugins.ITZTag",_Plugin,{
        label: "empty",
        number: 0,
	color:"#FFFFFF",
	_initButton: function(){
			editor = this.editor;
		this.button = new Button({
		        label: this.label,
    		        title: this.label+ " - Strg+"+(this.number+1),
			ownerDocument: editor.ownerDocument,
			dir: editor.dir,
			lang: editor.lang,
			showLabel: true,
			iconClass: this.iconClassPrefix + " " + this.iconClassPrefix + "ITZTag_" + this.label,
		        tabIndex: "-1",
			onClick: lang.hitch(this, "_tag")
		});
	},

	setEditor: function(editor){
		this.editor = editor;
		this._initButton();
        	console.log(editor._keyHandlers);
	        delete editor._keyHandlers[this.number+1];
		this.editor.addKeyHandler(this.number+1, true, false, lang.hitch(this, function(e){
		    console.log("Hier");
		    this._tag();
		}));
	},

//	updateState: function(){
//	},

	_tag: function(){
	    var ed=this.editor;
//	    console.log(this.label);
	    var NNode=document.createElement(this.label);
	    var SelRange=ed.window.getSelection().getRangeAt(0);
//	    ed.replaceValue(ed.value);
	    ed.beginEditing("");
	    NNode.appendChild(SelRange.extractContents());
	    SelRange.insertNode(NNode);
	    ed.endEditing("");
	}
});

// Register this plugin.
_Plugin.registry["itztag"] = function(args){
    return new ITZTag({label: args.label, color: args.color, number: args.number});
};


return ITZTag;
});
