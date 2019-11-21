<style scoped>
.simpleTab{
  margin-top:25px;
  padding-top:10px;
  padding-bottom:15px;
  background-color:#fff
}

.simpleTab.pop {
  border-radius:5px;
  box-shadow:1px 1px 2px 0 rgba(0,0,0,.13),0 1px 2px 0 rgba(0,0,0,.17)
}

.simpleTab > *{
  padding-left:25px;
  padding-right:25px
}

.simpleTab > :nth-child(1){ position:relative }
.simpleTab > :nth-child(1)::before{
  width:100%;
  right:0;
  bottom:0;
  content:'';
  position:absolute;
  border-bottom:1px solid rgba(67,67,67,.19)
}
.simpleTab header{
  /*border-bottom:1px solid rgba(0,0,0,.05)*/
  overflow:hidden;
  padding-bottom:10px;
}

.simpleTab header::after{
  left:0;
  width:100px;
  bottom:0;
  content:'';
  height:3px;
  position:absolute;
  transform:translateY(13%);
  box-sizing:content-box;
  transition:.3s cubic-bezier(.67,-0.88,.4,1.91);
  box-shadow:1px 1px 1px 0 rgba(67,67,67,.27),0 1px 0 0 rgba(67,67,67,.13);
  border-radius:3px;
  background-color:#ffca28
}


.simpleTab header > *{
  /*color:rgba(0,0,0,.27);*/
  padding:10px 3%;
  display:inline-block;
  transition:.15s ease-out;
  /*text-shadow:none;*/
  /*border-bottom:2px solid transparent*/
}

/*
.billingInput > header > .focus,.billingInput > header > .enter{
  color:inherit
}
.billingInput > header > .focus{
  text-shadow:inherit;
  border-bottom-color:#ffca28
}
*/
.simpleTab header h4{
  cursor:pointer;
  display:inline;
  transition:.15s ease-out;
  font-weight:600
}
.simpleTab header h4:hover{
}

.simpleTab > main{
  /*border:1px solid red;*/
  margin-top:15px
}

.simpleTab > main > :not(:last-child){
  margin-bottom:25px;
  border-bottom:1px solid #e6e6e6
}
</style>

<template>
<div class="simpleTab pop" style="position:relative">
  <div>
    <header>
      <div v-for="(e, i) of menu" :key=i>
        <h4>{{ e.title }}</h4>
      </div>
    </header>
  </div>
  <main>
    <review v-for="(e, i) of reviewers" :key=i v-bind=e />
    <xpander :titles="[ 'Spesifikasi', 'Deskripsi' ]">
      <template #0>
        <table style="margin:20px auto">
          <tr v-for="(e, i) of specs" :key=i>
            <td>{{ e.k }}</td>
            <td>{{ e.v }}</td>
          </tr>
        </table>
      </template>
      <template #1>
        <span style="display:block;white-space:pre-line;padding:20px 0;line-height:1.5em;font-size:.875em;margin:2px 0">Samsung SSD 970EVO M.2
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

Warranty : 5 years atau 150TBW</span>
      </template>
    </xpander>
  </main>
</div>
</template>

<script>
import Review from './ItemDetail/Review'
import Xpander from '@/components/Xpander'

export default {
   name:'simpleTab'
  ,data:() => ({
     reviewers:[
       {
         reviewer:'Murez Nasution'
        ,rating:3.86
        ,date:new Date(2019, 5, 7, 15, 30)
        ,text:'Recomended seller, jangan ragu belanja disini, seller nya enak kalau mau tanya tentang produk, packing rapi, dijamin barang diterima dalam keadaan baik'
      },{
         reviewer:'Emma Watson'
        ,rating:5
        ,date:new Date(2019, 5, 15, 21, 7)
        ,text:'Mantap , seller ramah & fast respond , pengiriman sangat cepat , rekomended seller dah ..mantapp poll'
      }
    ]
    ,specs:[
       { k:'Brand', v:'Apple' }
      ,{ k:'Item Height', v:'18 Millimeters' }
      ,{ k:'Item Width', v:'31.4 Centimeters' }
      ,{ k:'Screen Size', v:'13 Inches' }
      ,{ k:'Item Weight', v:'1.6 Kg' }
      ,{ k:'Product Dimensions', v:'21.9 x 31.4 x 1.8 cm' }
      ,{ k:'Item model number', v:'MF841HN/A' }
      ,{ k:'Processor Brand', v:'Intel' }
      ,{ k:'Processor Type', v:'Core i5' }
      ,{ k:'Processor Speed', v:'2.9 GHz' }
      ,{ k:'RAM Size', v:'8 GB' }
      ,{ k:'Hard Drive Size', v:'512 GB' }
      ,{ k:'Hard Disk Technology', v:'Solid State Drive' }
      ,{ k:'Graphics Coprocessor', v:'Intel Integrated Graphics' }
      ,{ k:'Graphics Card Description', v:'Integrated Graphics Card' }
      ,{ k:'Hardware Platform', v:'Mac' }
      ,{ k:'Operating System', v:'Mac OS' }
      ,{ k:'Average Battery Life (in hours)', v:'9' }
    ]
  })
  ,props:{
    menu:{ type:Array, default:() => [ ] }
  }
  ,components:{ Review, Xpander }
}
</script>