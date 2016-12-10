/// <reference path="../../../ts-src/types/systemjs/systemjs.d.ts" />
/// <reference path="../../../ts-src/types/jquery/jquery.d.ts" />
/// <reference path="../../../ts-src/types/foundation-sites/Foundation-Sites.d.ts" />
/// <reference path="../../../ts-src/types/codemirror/codemirror.d.ts" />
declare namespace OnxAtlas {
    class App {
        oEditCtrl: Object;
        constructor();
        onPageLoadBegin(): void;
        onPageLoadEnd(): void;
        setupCodemirror(oTextAreaElm: HTMLTextAreaElement, sCodeFileTypeExt: string, oConfig: any): void;
    }
}
