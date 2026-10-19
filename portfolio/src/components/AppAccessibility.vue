<script setup lang="ts">
import { computed, ref, nextTick } from "vue";
import AppHeader from "./AppHeader.vue";
import AppAccessibilityButton from "./AppAccessibilityButton.vue";

const props = defineProps({
	translation: {
		type: Object,
		required: true,
	},
	valuesTable: {
		type: Object,
		required: true,
	},
	showFilter: {
		type: Boolean,
		required: true,
	},
	showMenu: {
		type: Boolean,
		required: true,
	},
	colorTheme: {
		type: String,
		required: true,
	},
});

const emit = defineEmits<{
	updateAccessibility: [name: string, index: Number, show: boolean];
	showAccessibilityMenu: [newValue: Boolean];
}>();

function forwardUpdate(name: string, index: Number) {
	emit("updateAccessibility", name, index, true);
}

const showMenu = computed(() => props.showMenu);
const colorTheme = computed(() => props.colorTheme);

const accessibilityButtonRef = ref<HTMLButtonElement | null>(null);
const menuRef = ref<HTMLElement | null>(null);

function handleTabKey(e: KeyboardEvent) {
	if (!showMenu.value || !menuRef.value) return;

	const focusableElements = menuRef.value.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');

	if (focusableElements.length === 0) return;

	const firstElement = focusableElements[0] as HTMLElement;
	const lastElement = focusableElements[focusableElements.length - 1] as HTMLElement;

	if (e.shiftKey) {
		if (document.activeElement === firstElement) {
			e.preventDefault();
			accessibilityButtonRef.value?.focus();
		} else if (document.activeElement === accessibilityButtonRef.value) {
			e.preventDefault();
			lastElement.focus();
		}
	} else {
		if (document.activeElement === lastElement) {
			e.preventDefault();
			accessibilityButtonRef.value?.focus();
		} else if (document.activeElement === accessibilityButtonRef.value) {
			e.preventDefault();
			firstElement.focus();
		}
	}
}

async function handleAccessibilityMenu() {
	let newValue = !showMenu.value;
	emit("showAccessibilityMenu", newValue);

	if (newValue) {
		await nextTick();
		const firstBtn = menuRef.value?.querySelector('button, [tabindex]:not([tabindex="-1"])') as HTMLElement;
		firstBtn?.focus();
	} else {
		accessibilityButtonRef.value?.focus();
	}
}
</script>

<template>
	<button id="accessibilityButton" ref="accessibilityButtonRef" @click="handleAccessibilityMenu" @keydown.tab="handleTabKey">
		<picture>
			<source type="image/avif" :srcset="'/graphics/accessibility/accessibility-100.avif 100w, /graphics/accessibility/accessibility-200.avif 200w, /graphics/accessibility/accessibility-360.avif 360w'" sizes="120px" />
			<source type="image/webp" :srcset="'/graphics/accessibility/accessibility-100.webp 100w, /graphics/accessibility/accessibility-200.webp 200w, /graphics/accessibility/accessibility-360.webp 360w'" sizes="120px" />
			<img :srcset="'/graphics/accessibility/accessibility-100.png 100w, /graphics/accessibility/accessibility-200.png 200w, /graphics/accessibility/accessibility-360.png 360w'" sizes="120px" :src="'/graphics/accessibility/accessibility-200.png'" :alt="props.translation.accessibilityAlt" loading="lazy" decoding="async" width="120" height="120" />
		</picture>
	</button>

	<div class="overlay" id="accessibilityMenu" :style="`backdrop-filter: ` + (props.showFilter && showMenu ? `blur(calc(0.5 * var(--font-base)))` : `none`) + `; pointer-events: ` + (showMenu ? `all` : `none`)">
		<div v-if="showMenu" @click="handleAccessibilityMenu" id="clickable"></div>

		<aside v-if="showMenu" ref="menuRef" @keydown.tab="handleTabKey">
			<AppHeader :content="props.translation.menuTitle" />
			<div class="content">
				<AppAccessibilityButton v-for="(button, index) in translation.buttons" :buttonData="button" :index="Number(index)" :valuesTable="valuesTable" :colorTheme="colorTheme" @updateAccessibilityButton="forwardUpdate" />
			</div>
		</aside>
	</div>
</template>

<style scoped>
div.overlay {
	z-index: 3;
	pointer-events: none;
	display: flex;
	justify-content: center;
	align-items: center;
}

div#clickable {
	width: 100%;
	height: 100%;
	position: fixed;
	z-index: -1;
	cursor: pointer;
}

button#accessibilityButton {
	z-index: 4;
	position: fixed;
	bottom: max(calc(2.5 * var(--font-base)), env(safe-area-inset-bottom));
	right: max(calc(2.5 * var(--font-base)), env(safe-area-inset-right));
	border: none;
	background-color: transparent;
	cursor: pointer;
}
button#accessibilityButton picture,
button#accessibilityButton img {
	display: block;
	width: clamp(calc(5 * var(--font-base)), 10vw, calc(8 * var(--font-base)));
	height: auto;
	aspect-ratio: 1 / 1;
	-webkit-user-drag: none;
}

aside {
	background-color: var(--bg-accessibility);
	border: solid 2px var(--accent-color);
	display: flex;
	flex-direction: column;
	overflow-y: scroll;
}

aside > h2 {
	text-align: center;
	padding: calc(1.5 * var(--font-base)) 0 calc(0.8 * var(--font-base));
	position: relative;
	font-size: calc(2 * var(--font-base));
}
aside > h2::before {
	content: "";
	width: 90%;
	height: 2px;
	position: absolute;
	left: 5%;
	bottom: 0;
	background-color: var(--accent-color);
}

div.content {
	flex-grow: 1;
	width: 100%;
	padding: calc(1.5 * var(--font-base));
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(30%, 1fr));
	gap: calc(1.5 * var(--font-base));
	align-content: start;
}

@media (orientation: portrait) {
	aside {
		width: 80%;
		aspect-ratio: 3 / 4;
	}
}
@media (orientation: landscape) {
	aside {
		height: 80%;
		aspect-ratio: 4 / 3;
	}
}
</style>
