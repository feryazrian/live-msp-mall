<style>
#app .xpander{
  /*border:1px dotted red;*/
}

#app .xpander header{
  cursor:pointer;
  display:flex;
  padding:10px 15px;
  position:relative;
  align-items:center
}

#app .xpander header > :nth-child(1){
  width:25px;
  height:25px;
  position:relative;
  transform:rotateZ(90deg);
  transition:.5s cubic-bezier(.93,-1.01,.16,1.97);
  margin-right:7px
}
#app .xpander > .hidden > header > :nth-child(1){ transform:none }

#app .xpander header > :nth-child(2){
  margin:0;
  position:relative;
  flex-grow:1;
  transition:inherit;
  font-weight:600
}

#app .xpander header > :nth-child(2)::after{
  width:100%;
  right:50%;
  bottom:-10px;
  height:2px;
  content:'';
  position:absolute;
  transform:translateX(50%);
  transition:.5s ease-in-out;
  border-radius:1px;
  background-color:rgba(119,119,119,.67)
}
#app .xpander > .hidden > header > :nth-child(2)::after{
  width:0;
  opacity:0
}

#app .xpander main{
  display:flex;
  overflow:hidden;
  transition:.5s cubic-bezier(.18,.89,.32,1.28);
  padding-left:47px;
  padding-right:15px;
  flex-direction:column-reverse
}
#app .xpander > .hidden > main{
  transition-timing-function:cubic-bezier(.6,-0.28,.74,.05)
}

#app .xpander main > :only-child{
  position:relative;
  transition:inherit;
  transition-timing-function:ease-in-out
}
#app .xpander > .hidden > main > :only-child{
  opacity:0;
}

#app .xpander pre{
  white-space:pre-wrap
}
/*
#app .xpander .slide-enter-active,#app .xpander .slide-leave-active{
  transition:inherit;
  transition-delay:1s
}

#app .xpander .slide-enter,#app .xpander .slide-leave-to{
  opacity:0;
  transform:translateY(-100%)
}
*/
</style>

<template>
<div class=xpander ref=xpander>
  <section v-for="(e, i) of contents" :key=i>
    <header @click=xpand @mousedown=first>
      <div>
        <font-awesome icon=chevron-right class=cntr />
      </div>
      <h4>{{ e.title }}</h4>
    </header>
    <main>
      <div>
        <pre>{{ e.content }}</pre>
      </div>
    </main>
  </section>
</div>
</template>

<script>
import { calcHeight } from '@/assets/js/calcs'

export default {
   name:'xpander'
  ,methods:{
     xpand({ currentTarget:e }) {
      let child = e.parentElement.querySelector('main > :only-child')
      if((e = child.parentElement).getBoundingClientRect().height > 0) {
        e.style.height = 0
        e.parentElement.classList.add('hidden')
      } else {
        e.style.height = calcHeight(child) + 'px'
        e.parentElement.classList.remove('hidden')
      }
    }
    ,first({ currentTarget:e }) {
      let v = (e = e.parentElement.querySelector('main > :only-child')).parentElement
      if((v = v.style).height === '')
        v.height = calcHeight(e) + 'px'
    }
  }
  ,data() { return {
  	 contents:[
       { title:'Spesifikasi', content:`Samsung SSD 970EVO M.2
Capacity : 250GB
Model name : MZ-V7E250BW
Form Factor : M.2
Application : Client PC / Laptop
Interface : PCIe 3.0 x4, NVMe,
Slot / Socket : M Key
Suitable for gaming, workstation, design, 3D design

List of compatible Motherboard/Laptop for NVMe
https://www.tokopedia.com/atkiosk/note/788596/list-motherboard-dan-laptop-yang-dapat-menggunakan-ssd-nvme-wd-blackssd-atau-970evo-samsung

if you have others than that, feel free to ask us through diskusi / chat

Jika anda mencari produk untuk peningkatan kinerja PC / Laptop, Samsung 970EVO adalah pilihan yang sangat tepat. Interface NVMe menyediakan bandwidth yang lebih maju, dan controller baru serta teknologi generous treble Samsung's intelligent TurboWrite. Sudah termasuk solusi advanced maintenance software dan 5 tahun limited warranty.

Samsung 970EVO hadir dengan solusi Samsung Magician software terbaru untuk pengguna dapat monitor, mengatur dan memelihara SSD. Magician menyediakanfirmware checking dan tambahan fungsi yang dapat diatur untuk making-masing SSD pengguna, juga terus menyediakan update firmware secara automates untuk memastikan kinerja SSD tetap up to date

Samsung 970EVO menawarakan tingkat kinerja di penyimpanan untuk konsumen yang ingin pengalaman melebihi interface SATA. terlebih teknologi Intelligent TurboWrite technology terbaru mempercepat kemampuan write jauh dari sebelumnya.

Flash menggunakan V-NAND, 3D 3bit pertama di dunia
S.M.A.R.T support
Sequential Read : 3400MB/s
Sequential Write : 2300MB/s
Random Read : 370.000 IOPS
Random Write : 450.000 IOPS

MTBF : 1.5 million hours reliability
Software : Management Software Magician Software for SSD management

Warranty : 5 years atau 150TBW` }
      ,{ title:'Deskripsi', content:`Samsung SSD 970EVO M.2
Capacity : 250GB
Model name : MZ-V7E250BW
Form Factor : M.2
Application : Client PC / Laptop
Interface : PCIe 3.0 x4, NVMe,
Slot / Socket : M Key
Suitable for gaming, workstation, design, 3D design

List of compatible Motherboard/Laptop for NVMe
https://www.tokopedia.com/atkiosk/note/788596/list-motherboard-dan-laptop-yang-dapat-menggunakan-ssd-nvme-wd-blackssd-atau-970evo-samsung

if you have others than that, feel free to ask us through diskusi / chat

Jika anda mencari produk untuk peningkatan kinerja PC / Laptop, Samsung 970EVO adalah pilihan yang sangat tepat. Interface NVMe menyediakan bandwidth yang lebih maju, dan controller baru serta teknologi generous treble Samsung's intelligent TurboWrite. Sudah termasuk solusi advanced maintenance software dan 5 tahun limited warranty.

Samsung 970EVO hadir dengan solusi Samsung Magician software terbaru untuk pengguna dapat monitor, mengatur dan memelihara SSD. Magician menyediakanfirmware checking dan tambahan fungsi yang dapat diatur untuk making-masing SSD pengguna, juga terus menyediakan update firmware secara automates untuk memastikan kinerja SSD tetap up to date

Samsung 970EVO menawarakan tingkat kinerja di penyimpanan untuk konsumen yang ingin pengalaman melebihi interface SATA. terlebih teknologi Intelligent TurboWrite technology terbaru mempercepat kemampuan write jauh dari sebelumnya.

Flash menggunakan V-NAND, 3D 3bit pertama di dunia
S.M.A.R.T support
Sequential Read : 3400MB/s
Sequential Write : 2300MB/s
Random Read : 370.000 IOPS
Random Write : 450.000 IOPS

MTBF : 1.5 million hours reliability
Software : Management Software Magician Software for SSD management

Warranty : 5 years atau 150TBW` }
    ]
    ,text:`Samsung SSD 970EVO M.2
Capacity : 250GB
Model name : MZ-V7E250BW
Form Factor : M.2
Application : Client PC / Laptop
Interface : PCIe 3.0 x4, NVMe,
Slot / Socket : M Key
Suitable for gaming, workstation, design, 3D design

List of compatible Motherboard/Laptop for NVMe
https://www.tokopedia.com/atkiosk/note/788596/list-motherboard-dan-laptop-yang-dapat-menggunakan-ssd-nvme-wd-blackssd-atau-970evo-samsung

if you have others than that, feel free to ask us through diskusi / chat

Jika anda mencari produk untuk peningkatan kinerja PC / Laptop, Samsung 970EVO adalah pilihan yang sangat tepat. Interface NVMe menyediakan bandwidth yang lebih maju, dan controller baru serta teknologi generous treble Samsung's intelligent TurboWrite. Sudah termasuk solusi advanced maintenance software dan 5 tahun limited warranty.

Samsung 970EVO hadir dengan solusi Samsung Magician software terbaru untuk pengguna dapat monitor, mengatur dan memelihara SSD. Magician menyediakanfirmware checking dan tambahan fungsi yang dapat diatur untuk making-masing SSD pengguna, juga terus menyediakan update firmware secara automates untuk memastikan kinerja SSD tetap up to date

Samsung 970EVO menawarakan tingkat kinerja di penyimpanan untuk konsumen yang ingin pengalaman melebihi interface SATA. terlebih teknologi Intelligent TurboWrite technology terbaru mempercepat kemampuan write jauh dari sebelumnya.

Flash menggunakan V-NAND, 3D 3bit pertama di dunia
S.M.A.R.T support
Sequential Read : 3400MB/s
Sequential Write : 2300MB/s
Random Read : 370.000 IOPS
Random Write : 450.000 IOPS

MTBF : 1.5 million hours reliability
Software : Management Software Magician Software for SSD management

Warranty : 5 years atau 150TBW`
  } }
}
</script>