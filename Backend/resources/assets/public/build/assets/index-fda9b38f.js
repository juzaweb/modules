import{r as n,a as l,j as e,b as h}from"./app-ef304d51.js";import{b as x,m as d}from"./functions-7b46e371.js";import{A as T}from"./admin-f3d73062.js";import v from"./top-options-bef41953.js";import S from"./taxonomy-form-b5084830.js";import"./select-1bdea25d.js";import"./react-select.esm-d2671163.js";import"./input-e990643a.js";import"./checkbox-ff6ebd34.js";import"./button-a87aa463.js";function C({plugin:i,postTypes:u}){const[p,t]=n.useState(!1),[a,s]=n.useState(),c=r=>{r.preventDefault();let f=x("dev-tools/plugins/"+i.name+"/taxonomies"),g=new FormData(r.target);return t(!0),t(!1),h.post(f,g).then(m=>{let o=d(m);t(!1),s(o),(o==null?void 0:o.status)===!0&&r.target.reset(),setTimeout(()=>{s(void 0)},2e3)}).catch(m=>{s(d(m)),t(!1),setTimeout(()=>{s(void 0)},2e3)}),!1};return l(T,{children:[e(v,{moduleSelected:`plugins/${i.name}`,moduleType:"plugins",optionSelected:"taxonomies"}),e("div",{className:"row",children:l("div",{className:"col-md-12",children:[e("h5",{children:"Make Custom Taxonomy"}),a&&e("div",{className:`alert alert-${a.status?"success":"danger"} jw-message`,children:a.message}),e("form",{method:"POST",onSubmit:c,children:e(S,{buttonLoading:p,postTypes:u})})]})})]})}export{C as default};
