<template>
    <div class="">
        <Header />
        <slot></slot>
        <Footer />
    </div>
</template>

<script setup>
import Footer from "../components/shared/Footer.vue";
import Header from "../components/shared/Header.vue";
</script>
