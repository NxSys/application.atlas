
/// <reference path="types/systemjs/systemjs.d.ts" />
/// <reference path="types/jquery/jquery.d.ts" />
/// <reference path="types/foundation-sites/Foundation-Sites.d.ts" />
/// <reference path="types/codemirror/codemirror.d.ts" />

$(document).foundation();

// import {CodeMirror} from './types/codemirror/codemirror';

namespace OnxAtlas
{
	export class App
	{
		//public EventMgr;
		public oEditCtrl: Object;
		public constructor()
		{
		}
		public onPageLoadBegin()
		{	
			//@todo: init atv dom tree
			console.log('onPageLoadBegin');
		}
		public onPageLoadEnd()
		{
			console.log('onPageLoadEnd');			
		}
		//--- Appland
		public setupCodemirror(oTextAreaElm: HTMLTextAreaElement, sCodeFileTypeExt: string,
							   oConfig: any)
		{
			let x=SystemJS.import('vendor/codemirror/lib/codemirror.js');			
			console.log('Loading Ext '+sCodeFileTypeExt);						
			var oSuggestedMode: any;
			oSuggestedMode=CodeMirror.findModeByExtension(sCodeFileTypeExt);
			var y=SystemJS.import("vendor/codemirror/mode/"
							+oSuggestedMode.mode+"/"
							+oSuggestedMode.mode+".js");			
			oConfig.mode=oSuggestedMode.mode;
			this.oEditCtrl=CodeMirror.fromTextArea(oTextAreaElm, oConfig);
			// CodeMirror.autoLoadMode(this.oEditCtrl, oSuggestedMode);
			console.log(x);
			console.log(y);
			console.log(oSuggestedMode);
			console.log(this.oEditCtrl);
		}
	}
}

