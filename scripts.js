// scripts.js
document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('bookingForm');
  if(form){
    form.addEventListener('submit', function(e){
      const date = form.elements['appointment_date'].value;
      const time = form.elements['appointment_time'].value;
      if(!date || !time){ e.preventDefault(); alert('Preencha data e horário'); }
    });
  }
});
