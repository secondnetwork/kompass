class n extends HTMLElement{connectedCallback(){this.videoId=this.getAttribute("videoid");let e=this.querySelector(".lty-playbtn");if(this.playLabel=e&&e.textContent.trim()||this.getAttribute("playlabel")||"Play",this.style.backgroundImage||(this.style.backgroundImage=`url("https://i.ytimg.com/vi/${this.videoId}/hqdefault.jpg")`),e||(e=document.createElement("button"),e.type="button",e.classList.add("lty-playbtn"),this.append(e)),!e.textContent){const t=document.createElement("span");t.className="lyt-visually-hidden",t.textContent=this.playLabel,e.append(t)}e.removeAttribute("href"),this.addEventListener("pointerover",n.warmConnections,{once:!0}),this.addEventListener("click",this.addIframe),this.needsYTApiForAutoplay=navigator.vendor.includes("Apple")||navigator.userAgent.includes("Mobi")}static addPrefetch(e,t,a){const i=document.createElement("link");i.rel=e,i.href=t,a&&(i.as=a),document.head.append(i)}static warmConnections(){n.preconnected||(n.addPrefetch("preconnect","https://www.youtube-nocookie.com"),n.addPrefetch("preconnect","https://www.google.com"),n.addPrefetch("preconnect","https://googleads.g.doubleclick.net"),n.addPrefetch("preconnect","https://static.doubleclick.net"),n.preconnected=!0)}fetchYTPlayerApi(){window.YT||window.YT&&window.YT.Player||(this.ytApiPromise=new Promise((e,t)=>{var a=document.createElement("script");a.src="https://www.youtube.com/iframe_api",a.async=!0,a.onload=i=>{YT.ready(e)},a.onerror=t,this.append(a)}))}async addYTPlayerIframe(e){this.fetchYTPlayerApi(),await this.ytApiPromise;const t=document.createElement("div");this.append(t);const a=Object.fromEntries(e.entries());new YT.Player(t,{width:"100%",videoId:this.videoId,playerVars:a,events:{onReady:i=>{i.target.playVideo()}}})}async addIframe(){if(this.classList.contains("lyt-activated"))return;this.classList.add("lyt-activated");const e=new URLSearchParams(this.getAttribute("params")||[]);if(e.append("autoplay","1"),e.append("playsinline","1"),this.needsYTApiForAutoplay)return this.addYTPlayerIframe(e);const t=document.createElement("iframe");t.width=560,t.height=315,t.title=this.playLabel,t.allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture",t.allowFullscreen=!0,t.src=`https://www.youtube-nocookie.com/embed/${encodeURIComponent(this.videoId)}?${e.toString()}`,this.append(t),t.focus()}}customElements.define("lite-youtube",n);
