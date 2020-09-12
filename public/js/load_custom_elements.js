class toggleButton extends HTMLElement{
    constructor() {
        super();
        var t = document.querySelector('#toggle-input-template');
        this.clone = document.importNode(t.content, true);


    }
    connectedCallback(){
        this.appendChild(this.clone);
        this.input_checkbox=this.querySelector("input");
        var self=this;
        for (var att, i = 0, atts = this.attributes, n = atts.length; i < n; i++){
            att = atts[i];
            if(att.name!="name")
            this.input_checkbox.setAttribute(att.name,att.value);

        }
        this.input_checkbox.addEventListener("change",this.panchod);
        this.input_checkbox.object_name=this.getAttribute("name");
    }

    panchod(e){
        if(this.checked==true){
            this.setAttribute("name",e.currentTarget.object_name);
        }else{
            this.removeAttribute("name");

        }
    }


}


customElements.define("toggle-switch",toggleButton);