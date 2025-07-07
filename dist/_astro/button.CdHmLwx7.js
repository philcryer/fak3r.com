import{r as l}from"./index.CWXBSLiN.js";import{b as C,j as k,S as N,c as j}from"./index.Boahq4X5.js";/**
 * @license lucide-react v0.469.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const V=r=>r.replace(/([a-z0-9])([A-Z])/g,"$1-$2").toLowerCase(),x=(...r)=>r.filter((t,e,a)=>!!t&&t.trim()!==""&&a.indexOf(t)===e).join(" ").trim();/**
 * @license lucide-react v0.469.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */var A={xmlns:"http://www.w3.org/2000/svg",width:24,height:24,viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:2,strokeLinecap:"round",strokeLinejoin:"round"};/**
 * @license lucide-react v0.469.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const B=l.forwardRef(({color:r="currentColor",size:t=24,strokeWidth:e=2,absoluteStrokeWidth:a,className:s="",children:n,iconNode:u,...v},m)=>l.createElement("svg",{ref:m,...A,width:t,height:t,stroke:r,strokeWidth:a?Number(e)*24/Number(t):e,className:x("lucide",s),...v},[...u.map(([o,i])=>l.createElement(o,i)),...Array.isArray(n)?n:[n]]));/**
 * @license lucide-react v0.469.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const R=(r,t)=>{const e=l.forwardRef(({className:a,...s},n)=>l.createElement(B,{ref:n,iconNode:t,className:x(`lucide-${V(r)}`,a),...s}));return e.displayName=`${r}`,e},y=r=>typeof r=="boolean"?`${r}`:r===0?"0":r,g=C,E=(r,t)=>e=>{var a;if(t?.variants==null)return g(r,e?.class,e?.className);const{variants:s,defaultVariants:n}=t,u=Object.keys(s).map(o=>{const i=e?.[o],c=n?.[o];if(i===null)return null;const d=y(i)||y(c);return s[o][d]}),v=e&&Object.entries(e).reduce((o,i)=>{let[c,d]=i;return d===void 0||(o[c]=d),o},{}),m=t==null||(a=t.compoundVariants)===null||a===void 0?void 0:a.reduce((o,i)=>{let{class:c,className:d,...h}=i;return Object.entries(h).every(w=>{let[b,f]=w;return Array.isArray(f)?f.includes({...n,...v}[b]):{...n,...v}[b]===f})?[...o,c,d]:o},[]);return g(r,u,m,e?.class,e?.className)},O=E("inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50",{variants:{variant:{default:"bg-primary text-primary-foreground hover:bg-secondary/50",destructive:"bg-destructive text-destructive-foreground over:bg-destructive/50",outline:"border border-input bg-background hover:bg-secondary/50",secondary:"bg-secondary text-secondary-foreground hover:bg-secondary/80",ghost:"hover:bg-accent hover:text-accent-foreground",link:"text-primary underline-offset-4 hover:underline"},size:{default:"h-9 px-4 py-2",sm:"h-8 rounded-md px-3 text-xs",lg:"h-10 rounded-md px-8",icon:"h-9 w-9"}},defaultVariants:{variant:"default",size:"default"}}),$=l.forwardRef(({className:r,variant:t,size:e,asChild:a=!1,...s},n)=>{const u=a?N:"button";return k.jsx(u,{className:j(O({variant:t,size:e,className:r})),ref:n,...s})});$.displayName="Button";export{$ as B,O as b,R as c};
