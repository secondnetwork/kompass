/* empty css                */import{E as g,c as m,I as E,d as w,i as _,U as v}from"./vendor.ZmEsWFLr.js";const k="modulepreload",b=function(n){return"/assets/build/"+n},h={},u=function(c,s,d){let l=Promise.resolve();if(s&&s.length>0){const t=document.getElementsByTagName("link");l=Promise.all(s.map(e=>{if(e=b(e),e in h)return;h[e]=!0;const o=e.endsWith(".css"),f=o?'[rel="stylesheet"]':"";if(!!d)for(let r=t.length-1;r>=0;r--){const a=t[r];if(a.href===e&&(!o||a.rel==="stylesheet"))return}else if(document.querySelector(`link[href="${e}"]${f}`))return;const i=document.createElement("link");if(i.rel=o?"stylesheet":k,o||(i.as="script",i.crossOrigin=""),i.href=e,document.head.appendChild(i),o)return new Promise((r,a)=>{i.addEventListener("load",r),i.addEventListener("error",()=>a(new Error(`Unable to preload CSS for ${e}`)))})}))}return l.then(()=>c()).catch(t=>{const e=new Event("vite:preloadError",{cancelable:!0});if(e.payload=t,window.dispatchEvent(e),!e.defaultPrevented)throw t})},$=()=>({isEditing:!1,toggleEditingState(){this.isEditing=!this.isEditing},disableEditing(){this.isEditing=!1}});window.editorInstance=function(n,c,s,d,l){return{instance:null,data:null,initEditor(){this.data=this.$wire.$get(n),this.instance=new g({holder:c,minHeight:200,readOnly:s,placeholder:d,logLevel:l,tools:{header:m,table:{class:E,inlineToolbar:!1,config:{withHeadings:!0,rows:2,cols:3}},list:w,quote:_,underline:v},data:this.data,onChange:()=>{this.instance.save().then(t=>{this.$wire.$set(n,t)}).catch(t=>{console.log("Saving failed: ",t)})}})}}};document.getElementsByClassName("embed-video")&&(u(()=>import("./lite-yt-embed.CATR0rwC.js"),__vite__mapDeps([])),u(()=>import("./lite-vimeo-embed.BvH5bsO5.js"),__vite__mapDeps([])));Alpine.store("showside",{on:!1,toggle(){this.on=!this.on}});window.click_to_edit=$;
function __vite__mapDeps(indexes) {
  if (!__vite__mapDeps.viteFileDeps) {
    __vite__mapDeps.viteFileDeps = []
  }
  return indexes.map((i) => __vite__mapDeps.viteFileDeps[i])
}
