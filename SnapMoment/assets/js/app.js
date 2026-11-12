const $=id=>document.getElementById(id),modal=$('modal'),uploadModal=$('uploadModal'),form=$('visitorForm'),message=$('message'),nameInput=$('name'),photoInput=$('photoInput'),previewGrid=$('previewGrid'),uploadMessage=$('uploadMessage');let selectedFiles=[],activeVisitor=null,busy=false;
function show(el){el.hidden=false;document.body.classList.add('modal-open')}function hide(el){el.hidden=true;if(modal.hidden&&uploadModal.hidden)document.body.classList.remove('modal-open')}
$('start').onclick=()=>{show(modal);nameInput.focus()};$('close').onclick=()=>hide(modal);$('uploadClose').onclick=()=>hide(uploadModal);
[modal,uploadModal].forEach(el=>el.addEventListener('click',e=>{if(e.target===el)hide(el)}));document.addEventListener('keydown',e=>{if(e.key==='Escape'&&!busy){hide(uploadModal);hide(modal)}});
function openUpload(){hide(modal);show(uploadModal);$('welcomeText').textContent=`Welcome, ${activeVisitor.name}! Choose the moments you want to share.`;$('visitorBadge').hidden=false;$('visitorBadge').textContent=`Your Visitor ID: ${activeVisitor.code}`;}
form.addEventListener('submit',async e=>{e.preventDefault();let name=nameInput.value.trim();if(name.length<2){message.textContent='Enter at least 2 characters.';return;}let b=form.querySelector('button');b.disabled=true;message.textContent='Saving your profile...';try{let r=await fetch('api/identify.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name,visitor_code:document.getElementById('visitorCode').value.trim()})});let d=await r.json();if(!r.ok||!d.success)throw Error(d.message);activeVisitor=d.visitor;openUpload();loadMyPhotos();}catch(err){message.textContent=err.message;}finally{b.disabled=false;}});
$('backToName').onclick=()=>{hide(uploadModal);show(modal)};
function addFiles(list){let files=[...list],valid=files.filter(f=>/^image\/(jpeg|png|webp)$/.test(f.type));selectedFiles=[...selectedFiles,...valid].slice(0,20);render();if(valid.length!==files.length)uploadMessage.textContent='Only JPG, PNG and WEBP files are supported.';}
function render(){previewGrid.innerHTML='';selectedFiles.forEach((f,i)=>{let card=document.createElement('div');card.className='preview-card';let img=document.createElement('img');img.src=URL.createObjectURL(f);let x=document.createElement('button');x.className='remove-photo';x.textContent='×';x.onclick=()=>{selectedFiles.splice(i,1);render()};card.append(img,x);previewGrid.append(card);});if(selectedFiles.length)uploadMessage.textContent=`${selectedFiles.length} photo(s) selected.`;}
photoInput.onchange=e=>addFiles(e.target.files);const drop=$('dropzone');['dragenter','dragover'].forEach(t=>drop.addEventListener(t,e=>{e.preventDefault();drop.classList.add('dragging')}));['dragleave','drop'].forEach(t=>drop.addEventListener(t,e=>{e.preventDefault();drop.classList.remove('dragging')}));drop.addEventListener('drop',e=>addFiles(e.dataTransfer.files));
$('finishUpload').onclick=async()=>{if(busy)return;if(!selectedFiles.length){uploadMessage.textContent='Please choose at least one photo.';return;}if(!$('photoConsent').checked){uploadMessage.textContent='Please confirm the sharing consent.';return;}let fd=new FormData();selectedFiles.forEach(f=>fd.append('photos[]',f));fd.append('consent','1');busy=true;$('finishUpload').disabled=true;$('finishUpload').textContent='Uploading...';try{let r=await fetch('api/upload_photos.php',{method:'POST',body:fd});let d=await r.json();if(!r.ok||!d.success)throw Error(d.message);selectedFiles=[];render();$('photoConsent').checked=false;uploadMessage.textContent=d.message;$('uploadTitle').textContent='You did it!';$('finishUpload').textContent='Uploaded ✓';$('finishUpload').disabled=true;}catch(e){uploadMessage.textContent=e.message;$('finishUpload').textContent='Try again →';$('finishUpload').disabled=false;}finally{busy=false;}};
(async()=>{try{let r=await fetch('api/current_visitor.php');let d=await r.json();if(d.visitor){activeVisitor={...d.visitor};nameInput.value=d.visitor.name;document.querySelector('.helper').textContent=`Welcome back, ${d.visitor.name}. Your Visitor ID is ${d.visitor.code}.`;}}catch(e){}})();
async function loadMyPhotos(){try{const r=await fetch('api/my_photos.php');const d=await r.json();if(!d.success)return;let box=document.getElementById('myPreviousPhotos');if(!box){box=document.createElement('section');box.id='myPreviousPhotos';box.className='my-history';document.querySelector('.upload-dialog').appendChild(box);}box.innerHTML='<h3>Your previous uploads</h3>'+(d.photos.map(p=>`<article class="history-item"><img src="${p.photo_path}" alt="Your photo"><span class="status ${p.status}">${p.status}</span></article>`).join('')||'<p>No previous uploads yet.</p>');}catch(e){}}
let galleryCarouselTimer=null;
function setupGalleryCarousel(g){
  g.classList.add('gallery-carousel');
  g.setAttribute('aria-label','Approved event photos carousel');
  const originals=[...g.querySelectorAll('img')];
  originals.forEach((img,i)=>{img.classList.add('carousel-photo');img.loading='lazy';img.draggable=false;img.dataset.index=i;});
  // Only the database-backed original photos are shown; no DOM clones are created.
  const updateActive=target=>{
    originals.forEach(img=>img.classList.remove('is-focused','is-nearby'));
    if(!target)return;
    target.classList.add('is-focused');
    const i=originals.indexOf(target);
    if(i>0)originals[i-1].classList.add('is-nearby');
    if(i<originals.length-1)originals[i+1].classList.add('is-nearby');
  };
  g.addEventListener('pointerover',e=>{const img=e.target.closest('img');if(img&&originals.includes(img))updateActive(img);});
  g.addEventListener('pointerleave',()=>updateActive(null));
  const startAuto=()=>{
    if(originals.length<2||galleryCarouselTimer)return;
    galleryCarouselTimer=setInterval(()=>{
      if(!g.matches(':hover'))return;
      const first=originals[0];
      const step=first.getBoundingClientRect().width+18;
      const maxScroll=g.scrollWidth-g.clientWidth;
      if(g.scrollLeft>=maxScroll-8){g.scrollTo({left:0,behavior:'smooth'});}
      else g.scrollBy({left:step,behavior:'smooth'});
    },2600);
  };
  const stopAuto=()=>{if(galleryCarouselTimer){clearInterval(galleryCarouselTimer);galleryCarouselTimer=null;}};
  g.addEventListener('pointerenter',startAuto);
  g.addEventListener('pointerleave',stopAuto);
  g.addEventListener('touchstart',()=>g.classList.add('is-touching'),{passive:true});
  g.addEventListener('touchend',()=>setTimeout(()=>g.classList.remove('is-touching'),350),{passive:true});
}
async function loadGallery(){try{let r=await fetch('api/gallery.php');let d=await r.json();let g=$('publicGallery');if(!g)return;const photos=(d.photos||[]).slice(0,8);const markup=photos.map(p=>`<img src="${p.photo_path}" alt="Approved event photo">`).join('');if(g.dataset.signature===markup)return;g.dataset.signature=markup;g.innerHTML=markup||'<p>Approved photos will appear here.</p>';if(photos.length)setupGalleryCarousel(g);}catch(e){}}loadGallery();setInterval(loadGallery,15000);
