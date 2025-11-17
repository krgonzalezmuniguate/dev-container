import{k as p,I as s,J as d}from"./index-BRVX3OL0.js";import{h as l}from"./functions-B69Jx-_o.js";/**
 * @license lucide-vue-next v0.513.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const b=p("book-text",[["path",{d:"M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20",key:"k3hazp"}],["path",{d:"M8 11h8",key:"vwpz6n"}],["path",{d:"M8 7h6",key:"1f0q6e"}]]),r="http://161.1.0.3/auditoria/public/",k={downloadSolicitud(t){const a=s(),c=`${r}request_supplies_download/${t}`;d({url:c,method:"GET",responseType:"blob"}).then(o=>{const n=new Blob([o.data],{type:"application/pdf"}),e=document.createElement("a");e.href=window.URL.createObjectURL(n),e.download=`Solicitud_${t}.pdf`,e.click(),window.URL.revokeObjectURL(e.href)}).catch(o=>{l(o,a.setAlert)})}};export{b as B,k as p};
