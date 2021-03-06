description = Interleaving downtimes and multiple states, services
logfile = interleaving_states_service.log

global_vars {
	start_time = 400000001
	end_time = 400005000
}

worst state, down as down {
	service_description {
		host1;PING
		host2;PING
		host2;PING2
	}
	correct {
		TIME_OK_UNSCHEDULED = 139
		TIME_OK_SCHEDULED = 0
		TIME_WARNING_UNSCHEDULED = 700
		TIME_WARNING_SCHEDULED = 859
		TIME_CRITICAL_UNSCHEDULED = 2401
		TIME_CRITICAL_SCHEDULED = 900
	}
}
worst state, down as up {
	service_description {
		host1;PING
		host2;PING
		host2;PING2
	}
	scheduleddowntimeasuptime = 1
	correct {
		TIME_OK_UNSCHEDULED = 998
		TIME_OK_SCHEDULED = 0
		TIME_WARNING_UNSCHEDULED = 1600
		TIME_WARNING_SCHEDULED = 0
		TIME_CRITICAL_UNSCHEDULED = 2401
		TIME_CRITICAL_SCHEDULED = 0
	}
}
best state, down as down {
	service_description {
		host1;PING
		host2;PING
		host2;PING2
	}
	cluster_mode = 1
	correct {
		TIME_OK_UNSCHEDULED = 2799
		TIME_OK_SCHEDULED = 404
		TIME_WARNING_UNSCHEDULED = 1100
		TIME_WARNING_SCHEDULED = 696
		TIME_CRITICAL_UNSCHEDULED = 0
		TIME_CRITICAL_SCHEDULED = 0
	}
}
best state, down as up {
	service_description {
		host1;PING
		host2;PING
		host2;PING2
	}
	scheduleddowntimeasuptime = 1
	cluster_mode = 1
	correct {
		TIME_OK_UNSCHEDULED = 2799
		TIME_OK_SCHEDULED = 1100
		TIME_WARNING_UNSCHEDULED = 1100
		TIME_WARNING_SCHEDULED = 0
		TIME_CRITICAL_UNSCHEDULED = 0
		TIME_CRITICAL_SCHEDULED = 0
	}
}
