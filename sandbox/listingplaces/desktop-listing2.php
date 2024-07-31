<script src="https://unpkg.com/gsap@3/dist/gsap.min.js"></script>
<script src="https://unpkg.com/gsap@3/dist/ScrollTrigger.min.js"></script>
<script src="https://unpkg.com/gsap@3/dist/ScrollToPlugin.min.js"></script>

<style>
    
.listing-table-desktop img {
    object-fit: scale-down;
    width:90%;
    /*border: 1px solid;*/
}

body { 
  margin: 0; 
  overflow-x: hidden;
}

.dot-nav {
  position: fixed;
  z-index: 9999;
  display: flex;
  align-items: flex-end;
  justify-content: center;
  width: 100%;
  height: 100vh;
  margin-top: -20px;
}

.dot-nav li {
  list-style: none;
  margin: 0 10px;
  cursor: pointer;
}

.dot-nav li.is-active span {
  background: gray;
}

.dot-nav li span{
  display: inline-block;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  border: 1px solid #ffffff;
/*   background: gray; */
}

.red {
  background-color: #cf3535;
  background-image: none;
}

.yellow {
  background-color: yellow;
  background-image: none;
}

.slide {
  display: inline-block;
  color: white;
  width: 100vw;
  height: 100vh;
}

.slide:nth-child(1){
  background: #042A2B;
}

.slide:nth-child(2){
  background: #5EB1BF;
}

.slide:nth-child(3){
  background: #CDEDF6;
  color: #333333;
}

.slide:nth-child(4){
  background: #EF7B45;
}

.slide:nth-child(5){
  background: #D84727;
}

.wrapper {
  display: inline-block;
  white-space: nowrap;
  font-size: 0;
}
.wrapper > * {
  font-size: 1rem;
}

</style>

<script>
    
    gsap.registerPlugin(ScrollTrigger);

const sections = gsap.utils.toArray(".slide");
gsap.to(sections, {
  xPercent: -100 * (sections.length - 1),
  ease: "none",
  scrollTrigger: {
    trigger: ".wrapper",
    pin: true,
    scrub: true,
    snap: 1 / (sections.length - 1),
    // base vertical scrolling on how wide the container is so it feels more natural.
    end: () => "+=" + (document.querySelector(".wrapper").offsetWidth - innerWidth)
  }
});

document.querySelectorAll("nav li").forEach((anchor, i) => {
  anchor.addEventListener("click", function(e) {
    document.querySelector("li.is-active").classList.remove("is-active");
    anchor.classList.add("is-active");
    
    gsap.to(window, {
      scrollTo: {
        y: i * innerWidth,
        autoKill: false
      },
      duration: 1
    });
  });
});

    
</script>
<nav>
  <ul class="dot-nav">
    <li class="is-active"><span></span></li>
    <li><span></span></li>
    <li><span></span></li>
    <li><span></span></li>
    <li><span></span></li>
  </ul>
</nav>

<div class="wrapper">
  <section id="first" class="slide">
    <h1>First Section</h1>
    <p> This is a content.</p>          
  </section>
  <section id="second" class="slide">
    <h1>Second Section</h1>
    <p> This is a content.</p>          
  </section>
  <section id="third" class="slide one">
    <h1>Third Section</h1>
    <p> This is a content.</p>          
  </section>
  <section id="fourth" class="slide">
    <h1>Fourth Section</h1>
    <p> This is a content.</p>          
  </section>
  <section id="fifth" class="slide">
    <h1>Fifth Section</h1>
    <p> This is a content.</p>          
  </section>
</div>