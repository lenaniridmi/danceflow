<template>
    <footer class="footer">
      <p>{{ $t('FooterText') }} © {{ currentYear }} SmartCalendar</p>
    </footer>
  </template>
  
  <script>
  export default {
    name: 'Footer',
    computed: {
      currentYear() {
        return new Date().getFullYear();
      }
    }
  };
  </script>