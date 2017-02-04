
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
		public sTreeMapSelector: string;
		public oTreeMapInitOptions: TreeMapOptions;
		public oTreeMapCtrl: Object;
		public oEditCtrl: Object;
		public constructor()
		{
		}
		public onPageLoadBegin()
		{
			//@todo: init atv dom tree
			console.log('onPageLoadBegin_in');
			console.log('onPageLoadBegin_out');
		}
		public onPageLoadEnd()
		{
			console.log('onPageLoadEnd_in');

			this.useTreemap();

			console.log('onPageLoadEnd_out');
		}

		//--- Appland
		public setTreeMapSelector(sTreeMapSelector: string): void
		{
			this.sTreeMapSelector=sTreeMapSelector;
			return;
		}
		public setTreeMapInitOptions(oTMOpts: TreeMapOptions): void
		{
			this.oTreeMapInitOptions=oTMOpts;
			return;
		}
		public loadNodeDataForTreeMap(){}
		public useTreemap()
		{
			$(this.sTreeMapSelector).treemap(this.oTreeMapInitOptions);
			return;
		}
	}
}
interface JQuery
{
	treemap(Options: TreeMapOptions): any;
}
interface TreeMapOptions
{
	dimensions: any[];
	nodeData: any[];
}