/* empty css                */import{E as f,c as g,I as m,d as E,i as v,U as _}from"./vendor.FaN4dpSj.js";const S="modulepreload",I=function(n){return"/assets/build/"+n},u={},h=function(d,o,c){let l=Promise.resolve();if(o&&o.length>0){const t=document.getElementsByTagName("link");l=Promise.all(o.map(e=>{if(e=I(e),e in u)return;u[e]=!0;const s=e.endsWith(".css"),w=s?'[rel="stylesheet"]':"";if(!!c)for(let r=t.length-1;r>=0;r--){const a=t[r];if(a.href===e&&(!s||a.rel==="stylesheet"))return}else if(document.querySelector(`link[href="${e}"]${w}`))return;const i=document.createElement("link");if(i.rel=s?"stylesheet":S,s||(i.as="script",i.crossOrigin=""),i.href=e,document.head.appendChild(i),s)return new Promise((r,a)=>{i.addEventListener("load",r),i.addEventListener("error",()=>a(new Error(`Unable to preload CSS for ${e}`)))})}))}return l.then(()=>d()).catch(t=>{const e=new Event("vite:preloadError",{cancelable:!0});if(e.payload=t,window.dispatchEvent(e),!e.defaultPrevented)throw t})},k=()=>({isEditing:!1,toggleEditingState(){this.isEditing=!this.isEditing},disableEditing(){this.isEditing=!1}});window.editorInstance=function(n,d,o,c,l){return{instance:null,data:null,initEditor(){this.data=this.$wire.$get(n),this.instance=new f({holder:d,minHeight:200,readOnly:o,placeholder:c,logLevel:l,tools:{header:g,table:{class:m,inlineToolbar:!1,config:{withHeadings:!0,rows:2,cols:3}},list:E,quote:v,underline:_},data:this.data,onChange:()=>{this.instance.save().then(t=>{this.$wire.$set(n,t)}).catch(t=>{console.log("Saving failed: ",t)})}})}}};document.getElementsByClassName("embed-video")&&(h(()=>import("./lite-yt-embed.CATR0rwC.js"),__vite__mapDeps([])),h(()=>import("./lite-vimeo-embed.BvH5bsO5.js"),__vite__mapDeps([])));document.addEventListener("livewire:navigated",()=>{window.HSStaticMethods.autoInit(),window.HSAccordion.autoInit(),window.HSDropdown.autoInit(),window.HSOverlay.autoInit(),window.HSSelect.autoInit(),console.log("init")});Alpine.store("showside",{on:!1,toggle(){this.on=!this.on}});window.click_to_edit=k;
function __vite__mapDeps(indexes) {
  if (!__vite__mapDeps.viteFileDeps) {
    __vite__mapDeps.viteFileDeps = []
  }
  return indexes.map((i) => __vite__mapDeps.viteFileDeps[i])
}