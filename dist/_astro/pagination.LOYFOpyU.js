import{j as a,c as t}from"./index.Boahq4X5.js";import{r as m}from"./index.CWXBSLiN.js";import{c as x,b as y}from"./button.CdHmLwx7.js";/**
 * @license lucide-react v0.469.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const g=x("ChevronLeft",[["path",{d:"m15 18-6-6 6-6",key:"1wnfg3"}]]);/**
 * @license lucide-react v0.469.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const u=x("ChevronRight",[["path",{d:"m9 18 6-6-6-6",key:"mthhwq"}]]);/**
 * @license lucide-react v0.469.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const v=x("Ellipsis",[["circle",{cx:"12",cy:"12",r:"1",key:"41hilf"}],["circle",{cx:"19",cy:"12",r:"1",key:"1wjl8i"}],["circle",{cx:"5",cy:"12",r:"1",key:"1pcz8c"}]]),p=({className:s,...i})=>a.jsx("nav",{role:"navigation","aria-label":"pagination",className:t("mx-auto flex w-full justify-center",s),...i});p.displayName="Pagination";const d=m.forwardRef(({className:s,...i},e)=>a.jsx("ul",{ref:e,className:t("flex flex-row items-center gap-1",s),...i}));d.displayName="PaginationContent";const l=m.forwardRef(({className:s,...i},e)=>a.jsx("li",{ref:e,className:t("",s),...i}));l.displayName="PaginationItem";const r=({className:s,isActive:i,isDisabled:e,size:c="icon",...o})=>a.jsx("a",{"aria-current":i?"page":void 0,className:t(y({variant:i?"outline":"ghost",size:c}),e&&"pointer-events-none opacity-50",s),...o});r.displayName="PaginationLink";const h=({className:s,isDisabled:i,...e})=>a.jsxs(r,{"aria-label":"Go to previous page",size:"default",className:t("gap-1 pl-2.5",s),isDisabled:i,...e,children:[a.jsx(g,{className:"h-4 w-4"}),a.jsx("span",{children:"Previous"})]});h.displayName="PaginationPrevious";const j=({className:s,isDisabled:i,...e})=>a.jsxs(r,{"aria-label":"Go to next page",size:"default",className:t("gap-1 pr-2.5",s),isDisabled:i,...e,children:[a.jsx("span",{children:"Next"}),a.jsx(u,{className:"h-4 w-4"})]});j.displayName="PaginationNext";const f=({className:s,...i})=>a.jsxs("span",{"aria-hidden":!0,className:t("flex h-9 w-9 items-center justify-center",s),...i,children:[a.jsx(v,{className:"h-4 w-4"}),a.jsx("span",{className:"sr-only",children:"More pages"})]});f.displayName="PaginationEllipsis";const C=({currentPage:s,totalPages:i,baseUrl:e})=>{const c=Array.from({length:i},(n,N)=>N+1),o=n=>n===1?e:`${e}${n}`;return a.jsx(p,{children:a.jsxs(d,{className:"flex-wrap",children:[a.jsx(l,{children:a.jsx(h,{href:s>1?o(s-1):void 0,isDisabled:s===1})}),c.map(n=>a.jsx(l,{children:a.jsx(r,{href:o(n),isActive:n===s,children:n})},n)),i>5&&a.jsx(l,{children:a.jsx(f,{})}),a.jsx(l,{children:a.jsx(j,{href:s<i?o(s+1):void 0,isDisabled:s===i})})]})})};export{C as default};
