/// <reference path="../../../ts-src/types/systemjs/systemjs.d.ts" />
/// <reference path="../../../ts-src/types/jquery/jquery.d.ts" />
/// <reference path="../../../ts-src/types/foundation-sites/Foundation-Sites.d.ts" />
/// <reference path="../../../ts-src/types/codemirror/codemirror.d.ts" />
declare namespace OnxAtlas {
    class App {
        sTreeMapSelector: string;
        oTreeMapInitOptions: TreeMapOptions;
        oTreeMapCtrl: Object;
        oEditCtrl: Object;
        constructor();
        onPageLoadBegin(): void;
        onPageLoadEnd(): void;
        setTreeMapSelector(sTreeMapSelector: string): void;
        setTreeMapInitOptions(oTMOpts: TreeMapOptions): void;
        loadNodeDataForTreeMap(): void;
        useTreemap(): void;
    }
}
interface JQuery {
    treemap(Options: TreeMapOptions): any;
}
interface TreeMapOptions {
    dimensions: any[];
    nodeData: any[];
}
