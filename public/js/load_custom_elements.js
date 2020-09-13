class toggleButton extends HTMLElement{
    constructor() {
        super();
        var t = document.querySelector('#toggle-input-template');
        this.clone = document.importNode(t.content, true);


    }

    /**
     * Return the attributes to be observed here
     * @returns {string[]}
     */
    static get observedAttributes() {
        return ['checked'];
    }

    /**
     * Invokes everytime an observedattribute is changed
     * @param name name of the attribute
     * @param oldValue
     * @param newValue
     */
    attributeChangedCallback(name, oldValue, newValue) {
        //if the attribute is removed which means it's new value is null

       if(newValue==null){
           this.input_checkbox.checked=false;
           this.input_checkbox.removeAttribute("name");

       }
       //if the attribute is added
       else {
           this.input_checkbox.checked = true;
           this.input_checkbox.setAttribute("name", this.getAttribute("name"));
       }
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