#!/usr/bin/php
<?php

/*
 *  Prophecy can be called via { SSH -n -ouser=<user> <addr> <path-to-prophecy>  } // Completed.
 *  or
 *  by { SNMP_OID['Coming Soon'] }
 */

define('DeBug', 'False');

require_once "frigg-prophecy-db.class.php";
$prophecy = new prophecy();

$Argz = $prophecy->parseArgs($argv);
$tate = $prophecy->lockfile('check');

  if ( $tate==1 ) {
      $witch="locked";
    }
    else {
    if (isset($Argz[0])) {
      $witch=strtolower($Argz[0]);
    }
  }

switch ($witch) {
  case 'locked';
  echo "\nAnother instance is currently running\n\n";
  break;

  case 'full_monty'; // Get and show everything. /// To be continued ///
  $MontySql = array(
      "SHOW STATUS LIKE 'queries'",
      "SHOW STATUS LIKE 'connections'",
      "SHOW STATUS LIKE 'Bytes_received'",
      "SHOW STATUS LIKE 'Bytes_sent'"

  );
# DB Result
  $Result = $prophecy->frigg_full_monty($MontySql);

# Interface Result
  $Result .= "  " . trim(shell_exec("ifconfig eth0 | grep -i \"bytes\""));

# Http Server hits result
  $sName = "http://localhost/server-status/";
  $OutPutz = file_get_contents($sName);
  $txt='/(Total accesses: )(\\d+)/';
  $OutPut = preg_match_all ($txt, $OutPutz, $MyRezultz);
  $Result .= "  ApacheHits: {$MyRezultz[2][0]}  ";

# Number of Bad IPs
  $BadIPsql = "select count(badid) from bad_ip";
  $Result .= trim($prophecy->get_bad_ip_num($BadIPsql));
  echo "$Result\n";
  break;
}

/* ###
 * fopen :: $handle = fopen("http://www.example.com/", "r");
 * localhost/server-status
 * grep "requests" server-status
 *  Total accesses: (\d+);
 *           24 requests currently being processed, 297 idle workers
 * ###
 * Number of IP's added to bad_ip_num
 *           Query Number
 *
 *
* Too be added to Full Monty
* mysql> SHOW GLOBAL STATUS;
+-----------------------------------+------------+
| Variable_name                     | Value      |
+-----------------------------------+------------+
| Aborted_clients                   | 0          |
| Aborted_connects                  | 0          |
| Binlog_cache_disk_use             | 0          |
| Binlog_cache_use                  | 0          |
| Bytes_received                    | 0          |
| Bytes_sent                        | 0          |
| Com_admin_commands                | 0          |
| Com_assign_to_keycache            | 0          |
| Com_alter_db                      | 0          |
| Com_alter_db_upgrade              | 0          |
| Com_alter_event                   | 0          |
| Com_alter_function                | 0          |
| Com_alter_procedure               | 0          |
| Com_alter_server                  | 0          |
| Com_alter_table                   | 0          |
| Com_alter_tablespace              | 0          |
| Com_analyze                       | 0          |
| Com_backup_table                  | 0          |
| Com_begin                         | 0          |
| Com_binlog                        | 0          |
| Com_call_procedure                | 0          |
| Com_change_db                     | 0          |
| Com_change_master                 | 0          |
| Com_check                         | 0          |
| Com_checksum                      | 0          |
| Com_commit                        | 0          |
| Com_create_db                     | 0          |
| Com_create_event                  | 0          |
| Com_create_function               | 0          |
| Com_create_index                  | 0          |
| Com_create_procedure              | 0          |
| Com_create_server                 | 0          |
| Com_create_table                  | 0          |
| Com_create_trigger                | 0          |
| Com_create_udf                    | 0          |
| Com_create_user                   | 0          |
| Com_create_view                   | 0          |
| Com_dealloc_sql                   | 0          |
| Com_delete                        | 0          |
| Com_delete_multi                  | 0          |
| Com_do                            | 0          |
| Com_drop_db                       | 0          |
| Com_drop_event                    | 0          |
| Com_drop_function                 | 0          |
| Com_drop_index                    | 0          |
| Com_drop_procedure                | 0          |
| Com_drop_server                   | 0          |
| Com_drop_table                    | 0          |
| Com_drop_trigger                  | 0          |
| Com_drop_user                     | 0          |
| Com_drop_view                     | 0          |
| Com_empty_query                   | 0          |
| Com_execute_sql                   | 0          |
| Com_flush                         | 0          |
| Com_grant                         | 0          |
| Com_ha_close                      | 0          |
| Com_ha_open                       | 0          |
| Com_ha_read                       | 0          |
| Com_help                          | 0          |
| Com_insert                        | 0          |
| Com_insert_select                 | 0          |
| Com_install_plugin                | 0          |
| Com_kill                          | 0          |
| Com_load                          | 0          |
| Com_load_master_data              | 0          |
| Com_load_master_table             | 0          |
| Com_lock_tables                   | 0          |
| Com_optimize                      | 0          |
| Com_preload_keys                  | 0          |
| Com_prepare_sql                   | 0          |
| Com_purge                         | 0          |
| Com_purge_before_date             | 0          |
| Com_release_savepoint             | 0          |
| Com_rename_table                  | 0          |
| Com_rename_user                   | 0          |
| Com_repair                        | 0          |
| Com_replace                       | 0          |
| Com_replace_select                | 0          |
| Com_reset                         | 0          |
| Com_restore_table                 | 0          |
| Com_revoke                        | 0          |
| Com_revoke_all                    | 0          |
| Com_rollback                      | 0          |
| Com_rollback_to_savepoint         | 0          |
| Com_savepoint                     | 0          |
| Com_select                        | 0          |
| Com_set_option                    | 0          |
| Com_show_authors                  | 0          |
| Com_show_binlog_events            | 0          |
| Com_show_binlogs                  | 0          |
| Com_show_charsets                 | 0          |
| Com_show_collations               | 0          |
| Com_show_column_types             | 0          |
| Com_show_contributors             | 0          |
| Com_show_create_db                | 0          |
| Com_show_create_event             | 0          |
| Com_show_create_func              | 0          |
| Com_show_create_proc              | 0          |
| Com_show_create_table             | 0          |
| Com_show_create_trigger           | 0          |
| Com_show_databases                | 0          |
| Com_show_engine_logs              | 0          |
| Com_show_engine_mutex             | 0          |
| Com_show_engine_status            | 0          |
| Com_show_events                   | 0          |
| Com_show_errors                   | 0          |
| Com_show_fields                   | 0          |
| Com_show_function_status          | 0          |
| Com_show_grants                   | 0          |
| Com_show_keys                     | 0          |
| Com_show_master_status            | 0          |
| Com_show_new_master               | 0          |
| Com_show_open_tables              | 0          |
| Com_show_plugins                  | 0          |
| Com_show_privileges               | 0          |
| Com_show_procedure_status         | 0          |
| Com_show_processlist              | 0          |
| Com_show_profile                  | 0          |
| Com_show_profiles                 | 0          |
| Com_show_slave_hosts              | 0          |
| Com_show_slave_status             | 0          |
| Com_show_status                   | 0          |
| Com_show_storage_engines          | 0          |
| Com_show_table_status             | 0          |
| Com_show_tables                   | 0          |
| Com_show_triggers                 | 0          |
| Com_show_variables                | 0          |
| Com_show_warnings                 | 0          |
| Com_slave_start                   | 0          |
| Com_slave_stop                    | 0          |
| Com_stmt_close                    | 0          |
| Com_stmt_execute                  | 0          |
| Com_stmt_fetch                    | 0          |
| Com_stmt_prepare                  | 0          |
| Com_stmt_reprepare                | 0          |
| Com_stmt_reset                    | 0          |
| Com_stmt_send_long_data           | 0          |
| Com_truncate                      | 0          |
| Com_uninstall_plugin              | 0          |
| Com_unlock_tables                 | 0          |
| Com_update                        | 0          |
| Com_update_multi                  | 0          |
| Com_xa_commit                     | 0          |
| Com_xa_end                        | 0          |
| Com_xa_prepare                    | 0          |
| Com_xa_recover                    | 0          |
| Com_xa_rollback                   | 0          |
| Com_xa_start                      | 0          |
| Compression                       | Bool       |
| Connections                       | 0          |
| Created_tmp_disk_tables           | 0          |
| Created_tmp_files                 | 0          |
| Created_tmp_tables                | 0          |
| Delayed_errors                    | 0          |
| Delayed_insert_threads            | 0          |
| Delayed_writes                    | 0          |
| Flush_commands                    | 0          |
| Handler_commit                    | 0          |
| Handler_delete                    | 0          |
| Handler_discover                  | 0          |
| Handler_prepare                   | 0          |
| Handler_read_first                | 0          |
| Handler_read_key                  | 0          |
| Handler_read_next                 | 0          |
| Handler_read_prev                 | 0          |
| Handler_read_rnd                  | 0          |
| Handler_read_rnd_next             | 0          |
| Handler_rollback                  | 0          |
| Handler_savepoint                 | 0          |
| Handler_savepoint_rollback        | 0          |
| Handler_update                    | 0          |
| Handler_write                     | 0          |
| Innodb_buffer_pool_pages_data     | 0          |
| Innodb_buffer_pool_pages_dirty    | 0          |
| Innodb_buffer_pool_pages_flushed  | 0          |
| Innodb_buffer_pool_pages_free     | 0          |
| Innodb_buffer_pool_pages_misc     | 0          |
| Innodb_buffer_pool_pages_total    | 0          |
| Innodb_buffer_pool_read_ahead_rnd | 0          |
| Innodb_buffer_pool_read_ahead_seq | 0          |
| Innodb_buffer_pool_read_requests  | 0          |
| Innodb_buffer_pool_reads          | 0          |
| Innodb_buffer_pool_wait_free      | 0          |
| Innodb_buffer_pool_write_requests | 0          |
| Innodb_data_fsyncs                | 0          |
| Innodb_data_pending_fsyncs        | 0          |
| Innodb_data_pending_reads         | 0          |
| Innodb_data_pending_writes        | 0          |
| Innodb_data_read                  | 0          |
| Innodb_data_reads                 | 0          |
| Innodb_data_writes                | 0          |
| Innodb_data_written               | 0          |
| Innodb_dblwr_pages_written        | 0          |
| Innodb_dblwr_writes               | 0          |
| Innodb_log_waits                  | 0          |
| Innodb_log_write_requests         | 0          |
| Innodb_log_writes                 | 0          |
| Innodb_os_log_fsyncs              | 0          |
| Innodb_os_log_pending_fsyncs      | 0          |
| Innodb_os_log_pending_writes      | 0          |
| Innodb_os_log_written             | 0          |
| Innodb_page_size                  | 0          |
| Innodb_pages_created              | 0          |
| Innodb_pages_read                 | 0          |
| Innodb_pages_written              | 0          |
| Innodb_row_lock_current_waits     | 0          |
| Innodb_row_lock_time              | 0          |
| Innodb_row_lock_time_avg          | 0          |
| Innodb_row_lock_time_max          | 0          |
| Innodb_row_lock_waits             | 0          |
| Innodb_rows_deleted               | 0          |
| Innodb_rows_inserted              | 0          |
| Innodb_rows_read                  | 0          |
| Innodb_rows_updated               | 0          |
| Key_blocks_not_flushed            | 0          |
| Key_blocks_unused                 | 0          |
| Key_blocks_used                   | 0          |
| Key_read_requests                 | 0          |
| Key_reads                         | 0          |
| Key_write_requests                | 0          |
| Key_writes                        | 0          |
| Last_query_cost                   | Float      |
| Max_used_connections              | 0          |
| Not_flushed_delayed_rows          | 0          |
| Open_files                        | 0          |
| Open_streams                      | 0          |
| Open_table_definitions            | 0          |
| Open_tables                       | 0          |
| Opened_files                      | 0          |
| Opened_table_definitions          | 0          |
| Opened_tables                     | 0          |
| Prepared_stmt_count               | 0          |
| Qcache_free_blocks                | 0          |
| Qcache_free_memory                | 0          |
| Qcache_hits                       | 0          |
| Qcache_inserts                    | 0          |
| Qcache_lowmem_prunes              | 0          |
| Qcache_not_cached                 | 0          |
| Qcache_queries_in_cache           | 0          |
| Qcache_total_blocks               | 0          |
| Queries                           | 0          |
| Questions                         | 0          |
| Rpl_status                        | NULL       |
| Select_full_join                  | 0          |
| Select_full_range_join            | 0          |
| Select_range                      | 0          |
| Select_range_check                | 0          |
| Select_scan                       | 0          |
| Slave_open_temp_tables            | 0          |
| Slave_retried_transactions        | 0          |
| Slave_running                     | OFF        |
| Slow_launch_threads               | 0          |
| Slow_queries                      | 0          |
| Sort_merge_passes                 | 0          |
| Sort_range                        | 0          |
| Sort_rows                         | 0          |
| Sort_scan                         | 0          |
| Ssl_accept_renegotiates           | 0          |
| Ssl_accepts                       | 0          |
| Ssl_callback_cache_hits           | 0          |
| Ssl_cipher                        |            |
| Ssl_cipher_list                   |            |
| Ssl_client_connects               | 0          |
| Ssl_connect_renegotiates          | 0          |
| Ssl_ctx_verify_depth              | 0          |
| Ssl_ctx_verify_mode               | 0          |
| Ssl_default_timeout               | 0          |
| Ssl_finished_accepts              | 0          |
| Ssl_finished_connects             | 0          |
| Ssl_session_cache_hits            | 0          |
| Ssl_session_cache_misses          | 0          |
| Ssl_session_cache_mode            | NONE       |
| Ssl_session_cache_overflows       | 0          |
| Ssl_session_cache_size            | 0          |
| Ssl_session_cache_timeouts        | 0          |
| Ssl_sessions_reused               | 0          |
| Ssl_used_session_cache_entries    | 0          |
| Ssl_verify_depth                  | 0          |
| Ssl_verify_mode                   | 0          |
| Ssl_version                       |            |
| Table_locks_immediate             | 0          |
| Table_locks_waited                | 0          |
| Tc_log_max_pages_used             | 0          |
| Tc_log_page_size                  | 0          |
| Tc_log_page_waits                 | 0          |
| Threads_cached                    | 0          |
| Threads_connected                 | 0          |
| Threads_created                   | 0          |
| Threads_running                   | 0          |
| Uptime                            | 0          |
| Uptime_since_flush_status         | 0          |
+-----------------------------------+------------+
291 rows in set (0.07 sec)

mysql> select count(badid) from bad_ip;
+--------------+
| count(badid) |
+--------------+
|     35439683 |
+--------------+
1 row in set (0.00 sec)


 */

