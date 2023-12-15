const input = document.querySelector("textarea");
const wordCount = document.querySelector("[data-word-count]");
const characterCount = document.querySelector("[data-character-count]");
const sentenceCount = document.querySelector("[data-sentence-count]");
const paragraphCount = document.querySelector("[data-paragraph-count]");

input.addEventListenerZ("input", function(){
  if(input.value){
    const wordsArray = input.value.split(" ").filter((word)=>word !== "");
    wordCount.innerText = wordsArray.length;
    characterCount.innerText = input.value.length;
    const sentenceArray = input.value.split(/[.!]/);
    sentenceCount.innerText = sentenceArray.length -1;

    const paragraphArray = input.value.split("\n").filter((p)=> p.trim() !=="");
    paragraphCount.innerText = paragraphArray.length;
  }
  else{
    wordCount.innerText=
      characterCount.innerText=
      sentenceCount.innerText=
      paragraphCount.innerText=
      0;
  }
});