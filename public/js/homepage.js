    $(".navbar").load("navbar.html");

function moneyDispenser(amount){
  this.amount=amount;
}

moneyDispenser.prototype={
  print:()=>{
    console.log("hi "+this.amount);
  }
}

const m=new moneyDispenser(200);
m.print();