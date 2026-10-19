<script setup lang="ts">
import AppHeader from "./AppHeader.vue";

let props = defineProps({
	title: {
		type: String,
		required: true,
	},
	type: {
		type: Number,
		default: 2,
	},
	list: {
		type: Object,
		required: true,
	},
});
</script>

<template>
	<AppHeader :content="title" :type="4" />
	<ul>
		<li v-for="element in list">{{ element }}</li>
	</ul>
</template>

<style scoped>
ul {
	padding-left: calc(1.5 * var(--font-base));
}
</style>
