import{k as s,I as d,J as l}from"./index-CwaQPSV_.js";import{h as p}from"./functions-BSmh5TsC.js";/**
 * @license lucide-vue-next v0.513.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const b=s("book-text",[["path",{d:"M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20",key:"k3hazp"}],["path",{d:"M8 11h8",key:"vwpz6n"}],["path",{d:"M8 7h6",key:"1f0q6e"}]]),r="/auditoria/public/",k={downloadSolicitud(t){const a=d(),c=`${r}request_supplies_download/${t}`;l({url:c,method:"GET",responseType:"blob"}).then(o=>{const n=new Blob([o.data],{type:"application/pdf"}),e=document.createElement("a");e.href=window.URL.createObjectURL(n),e.download=`Solicitud_${t}.pdf`,e.click(),window.URL.revokeObjectURL(e.href)}).catch(o=>{p(o,a.setAlert)})}};export{b as B,k as p};
