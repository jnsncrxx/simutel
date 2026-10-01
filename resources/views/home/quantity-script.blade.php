<script>
    /*--------------------------QUANTITY--------------------------*/
const dec = document.getElementsByClassName('input-number-decrement')
      numInput = document.getElementsByClassName('input-number')
      inc = document.getElementsByClassName('input-number-increment');

for(let i = 0; i<3; i++){

      dec[i].addEventListener("click", () => {
        numInput[i].value -= 1;

        if(numInput[i].value < 1 && i != 2){
          numInput[i].value = 1;
        }
        else if(numInput[2].value < 1)
          numInput[2].value = 0;
          
        setNumValue();
      })

      inc[i].addEventListener("click", () => {
        numInput[i].value = parseInt(numInput[i].value) + 1;

        setNumValue();
      })

      function setNumValue (){
          numInput.value = numInput[i].value;
      };
}
/*--------------------------------------------------------------*/
</script>