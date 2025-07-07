import{j as e}from"./index.Boahq4X5.js";import{c as r,B as i}from"./button.CdHmLwx7.js";import{D as l,a as d,b as h,c as n}from"./dropdown-menu.Cf408FjQ.js";import{r as o}from"./index.CWXBSLiN.js";import"./index.bNX8c49m.js";import"./index.WPhvqga_.js";import"./index.BKOj0puO.js";/**
 * @license lucide-react v0.469.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const p=r("Laptop",[["path",{d:"M20 16V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v9m16 0H4m16 0 1.28 2.55a1 1 0 0 1-.9 1.45H3.62a1 1 0 0 1-.9-1.45L4 16",key:"tarvll"}]]);/**
 * @license lucide-react v0.469.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const c=r("Moon",[["path",{d:"M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z",key:"a7tn18"}]]);/**
 * @license lucide-react v0.469.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const m=r("Sun",[["circle",{cx:"12",cy:"12",r:"4",key:"4exip2"}],["path",{d:"M12 2v2",key:"tus03m"}],["path",{d:"M12 20v2",key:"1lh1kg"}],["path",{d:"m4.93 4.93 1.41 1.41",key:"149t6j"}],["path",{d:"m17.66 17.66 1.41 1.41",key:"ptbguv"}],["path",{d:"M2 12h2",key:"1t8f8n"}],["path",{d:"M20 12h2",key:"1q8mjw"}],["path",{d:"m6.34 17.66-1.41 1.41",key:"1m8zz5"}],["path",{d:"m19.07 4.93-1.41 1.41",key:"1shlcs"}]]);function f(){const[s,t]=o.useState("theme-light");return o.useEffect(()=>{const a=document.documentElement.classList.contains("dark");t(a?"dark":"theme-light")},[]),o.useEffect(()=>{const a=s==="dark"||s==="system"&&window.matchMedia("(prefers-color-scheme: dark)").matches;document.documentElement.classList.add("disable-transitions"),document.documentElement.classList[a?"add":"remove"]("dark"),window.getComputedStyle(document.documentElement).getPropertyValue("opacity"),requestAnimationFrame(()=>{document.documentElement.classList.remove("disable-transitions")})},[s]),e.jsxs(l,{modal:!1,children:[e.jsx(d,{asChild:!0,children:e.jsxs(i,{variant:"outline",size:"icon",className:"group",title:"Toggle theme",children:[e.jsx(m,{className:"size-4 rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0"}),e.jsx(c,{className:"absolute size-4 rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100"}),e.jsx("span",{className:"sr-only",children:"Toggle theme"})]})}),e.jsxs(h,{align:"end",className:"bg-background",children:[e.jsxs(n,{onClick:()=>t("theme-light"),children:[e.jsx(m,{className:"mr-2 size-4"}),e.jsx("span",{children:"Light"})]}),e.jsxs(n,{onClick:()=>t("dark"),children:[e.jsx(c,{className:"mr-2 size-4"}),e.jsx("span",{children:"Dark"})]}),e.jsxs(n,{onClick:()=>t("system"),children:[e.jsx(p,{className:"mr-2 size-4"}),e.jsx("span",{children:"System"})]})]})]})}export{f as ModeToggle};
