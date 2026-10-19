import { ref } from "vue";

export interface Alert {
	id: number;
	message: string;
	srOnly?: boolean;
}

const alerts = ref<Alert[]>([]);

export function useAlerts() {
	const removeAlert = (id: number) => {
		alerts.value = alerts.value.filter((alert) => alert.id !== id);
	};
	const addAlert = (message: string, duration = 3000, srOnly = false) => {
		const id = Date.now() + Math.random();
		setTimeout(() => {
			alerts.value.unshift({ id, message, srOnly });

			setTimeout(() => {
				removeAlert(id);
			}, duration);
		}, 50);
	};
	return { alerts, addAlert, removeAlert };
}
