<style>
#app .simpleFooter .searchButton::before,#app .simpleFooter .searchButton > :only-child::before{
  background-color:#424242
}
</style>

<style scoped>
.simpleFooter{
  padding-top:100px;
  text-shadow:1px 1px 1px rgba(67,67,67,.23)
}

 .simpleFooter > .footerLinks
,.simpleFooter > :last-child > :only-child{
  padding-left:15px;
  padding-right:15px
}
 .simpleFooter > .footerLinks.desktop
,.simpleFooter > :last-child > :only-child.desktop{
  padding-left:0;
  padding-right:0
}

.simpleFooter > .footerLinks h5{
  margin:0 0 20px;
  font-size:1.05em;
  font-weight:500
}

.simpleFooter > .footerLinks:nth-child(1) > *{
  display:inline-block;
  vertical-align:top
}

.simpleFooter > .footerLinks:nth-child(1) > :nth-child(1) {
  width:25%
}

.simpleFooter > .footerLinks:nth-child(1) > :nth-child(1) > img{
  width:125px;
  height:55px;
  object-fit:cover
}

.simpleFooter > .footerLinks:nth-child(1) > :nth-child(1) > :last-child{
  display:flex;
  margin-top:15px;
  align-items:center
}

.simpleFooter > .footerLinks:nth-child(1) > :nth-child(1) > :last-child > img{
  width:50px;
  flex-shrink:0;
  margin-right:10px
}

.simpleFooter > .footerLinks:nth-child(1) > :nth-child(1) > :last-child > :last-child{
  flex-grow:1
}

.simpleFooter > .footerLinks:nth-child(1) > :nth-child(1) > :last-child > :last-child > :nth-child(1){
  font-size:.75em
}
.simpleFooter > .footerLinks:nth-child(1) > :nth-child(1) > :last-child > :last-child > :nth-child(2){
  color:rgb(67,67,67);
  font-size:1.4em
}


.simpleFooter > .footerLinks:nth-child(1) > ul {
  width:75%;
  padding-left:10px
}

.simpleFooter > .footerLinks:nth-child(1) > ul > *{
  width:25%;
  display:inline-block;
  vertical-align:top
}

.simpleFooter > .footerLinks:nth-child(1) > ul li{
  margin:10px 0;
  cursor:pointer;
  font-size:.8em
}
.simpleFooter > .footerLinks:nth-child(1) > ul li > a{
  color:inherit;
  text-decoration:none
}


.simpleFooter > .footerLinks:nth-child(2){
  margin-top:50px
}

.simpleFooter > .footerLinks:nth-child(2) > *{
  display:inline-block;
  vertical-align:top
}

.simpleFooter > .footerLinks:nth-child(2) > :nth-child(1){
  width:25%
}

.simpleFooter > .footerLinks:nth-child(2) > :nth-child(2){
  width:37.5%;
  padding-left:10px
}

.simpleFooter > .footerLinks:nth-child(2) > :nth-child(3){
  width:37.5%;
  padding-left:10px
}

.simpleFooter > .footerLinks:nth-child(2) > :nth-child(1) > *{
  /*margin:25px;*/
  width:33.3%;
  color:inherit;
  display:inline-block;
  position:relative;
  font-size:0
}

.simpleFooter > .footerLinks:nth-child(2) > :nth-child(1) > * > *{
  font-size:31px
}

.simpleFooter > .footerLinks:nth-child(2) > :nth-child(2) > nav{
  font-size:0
}



.simpleFooter > :nth-child(3){
  top:0;
  right:50%;
  position:absolute;
  transform:translate(50%,-50%)
}

.simpleFooter .subscription{
  margin:0 auto;
  padding:11px 1.5%;
  position:relative;
}

.simpleFooter > .desktop > .subscription{
  width:85%;
  border-radius:13px
}

.simpleFooter .subscription > *{
  display:inline-block;
  vertical-align:middle
}

.simpleFooter .subscription > :nth-child(1){
  width:30%;
  font-size:1.3em
}

.simpleFooter .subscription > :nth-child(2){
  width:25%;
  font-size:.85em;
  text-align:right;
  padding-right:3%
}

.simpleFooter .subscription > .searchBox{
  width:45%
}

.simpleFooter > :last-child{
  font-size:.8em;
  margin-top:75px;
  background-color:#d9d9d9
}

.simpleFooter > :last-child > :only-child{
  padding-top:35px;
  padding-bottom:40px
}

.simpleFooter > .footerLinks:nth-child(2) nav > img{
  height:35px;
  display:inline-block;
  object-fit:contain;
  margin-right:10px;
  margin-bottom:10px
}

.simpleFooter > .footerLinks:nth-child(2) nav > img[alt=GoPay]{ width:60px }
.simpleFooter > .footerLinks:nth-child(2) nav > img[alt=Midtrans]{ width:85px }
.simpleFooter > .footerLinks:nth-child(2) nav > img[alt=Kredivo]{ width:50px }
.simpleFooter > .footerLinks:nth-child(2) nav > img[alt=MasterCard]{ width:50px }
.simpleFooter > .footerLinks:nth-child(2) nav > img[alt=Visa]{ width:45px }
.simpleFooter > .footerLinks:nth-child(2) nav > img[alt=Mandiri]{ width:65px }
.simpleFooter > .footerLinks:nth-child(2) nav > img[alt=BNI]{ width:45px }
.simpleFooter > .footerLinks:nth-child(2) nav > img[alt=Permata]{ width:85px }
.simpleFooter > .footerLinks:nth-child(2) nav > img[alt=LifePoint]{ width:85px }
.simpleFooter > .footerLinks:nth-child(2) nav > img[alt=MonsWallet]{ width:120px }

.simpleFooter > .footerLinks:nth-child(2) nav > img[alt=JNE]{ width:55px }
.simpleFooter > .footerLinks:nth-child(2) nav > img[alt=Tiki]{ width:55px }
.simpleFooter > .footerLinks:nth-child(2) nav > img[alt='J&T']{ width:55px }
</style>

<template>
<div class="simpleFooter"
style="position:relative;background-color:#e6e6e6;margin-top:50px">
  <page class=footerLinks>
    <div>
      <img :src="imagePath('mall.png')" alt="MyMSPMall" />
      <div>
        <img :src="imagePath('cs.png')" alt="Headset" />
        <div>
          <div>Butuh bantuan? Hubungi</div>
          <div>061&#160;6644799</div>
        </div>
      </div>
    </div>
    <ul class=app__basic_theme__color_hover>
      <ul v-for="i in links" :key=i.id>
        <h5>{{ i.name }}</h5>
        <li v-for="j of i.links" :key=j.url><a :href=j.url>{{ j.name }}</a></li>
      </ul>
    </ul>
  </page>
  <page class=footerLinks>
    <div>
      <a href=https://www.facebook.com/mymspmall.id target=_blank><font-awesome class=center :icon="[ 'fab', 'facebook-square' ]" /></a>
      <a href=https://www.instagram.com/mymspmall.id target=_blank><font-awesome class=center :icon="[ 'fab', 'instagram' ]" /></a>
      <a href=https://www.youtube.com/channel/UCblejBpzeaAATIt8JgAJVGw target=_blank><font-awesome class=center :icon="[ 'fab', 'youtube' ]" /></a>
    </div>
    <section>
      <h5>Metode Pembayaran</h5>
      <nav>
        <img :src="imagePath('gopay.png')" alt="GoPay" />
        <img :src="imagePath('midtrans.png')" alt="Midtrans" />
        <img :src="imagePath('kredivo.png')" alt="Kredivo" />
        <img :src="imagePath('mastercard.png')" alt="MasterCard" />
        <img :src="imagePath('visa.png')" alt="Visa" />
        <img :src="imagePath('mandiri.png')" alt="Mandiri" />
        <img :src="imagePath('bni.png')" alt="BNI" />
        <img :src="imagePath('permata.png')" alt="Permata" />
        <img :src="imagePath('life.png')" alt="LifePoint" />
        <img :src="imagePath('monswallet.png')" alt="MonsWallet" />
      </nav>
    </section>
    <section>
      <h5>Jasa Pengiriman</h5>
      <nav>
        <img :src="imagePath('jne.png')" alt="JNE" />
        <img :src="imagePath('tiki.png')" alt="Tiki" />
        <img :src="imagePath('j&t.png')" alt="J&T" />
      </nav>
    </section>
  </page>
  <page>
    <div class="themeBasis-bgColor subscription" style="display:none">
      <div>Masuk MSP Forum</div>
      <div>...dapatkan <i style="font-weight:700;font-style:normal;font-size:1.1em">3&#160;MSP</i></div>
      <banana class=searchBox cssClass=searchButton label="Masukkan E-mail Kamu">
        <font-awesome icon=search style=color:#fff />
      </banana>
      <!-- <div class=searchBox>
        <input class=app__basic_theme type=text placeholder="Masukkan E-mail Kamu" />
        <button class=app__basic_theme><font-awesome icon=search class=center /></button>
      </div> -->
    </div>
  </page>
  <div>
    <page style=text-align:center>&copy; {{ new Date().getFullYear() }} PT. Monspace Mega Indonesia. Seluruh Hak Cipta Terlindungi.</page>
  </div>
</div>
</template>

<script>
import { PATH, getFooterLinks } from '@/assets/js/fetchers'
import Banana from '@/components/Banana'

export default {
   name:'simpleFooter'
  ,data:() => ({ links:{ } })
  ,methods:{ imagePath:v => PATH.image(v) }
  ,mounted() {
    getFooterLinks()
      .then(data => {
        if(data.code === 200) {
          this.links = data.items
        } else
          console.log('Footer links not available')
      }).catch(e => { console.log(e) })
  }
  ,components:{ Banana }
}
</script>