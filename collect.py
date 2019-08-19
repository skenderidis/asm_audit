import os
import datetime
import requests
from requests.auth import HTTPDigestAuth
import json
import getpass
import sys

requests.packages.urllib3.disable_warnings() 

print(sys.argv[5])
bigip = sys.argv[1]
username = sys.argv[2]
password = sys.argv[3]
asm_policy_name = sys.argv[4]
asm_policy_id = sys.argv[5]
asm_policy_type = sys.argv[6]

from f5_functions import *		

	##### if Policy is not Parent Pocliy ######
if asm_policy_type == "security":
	
	########################  Overview  ########################
	if not (os.path.exists("config_files/"+asm_policy_name)):
		os.makedirs("config_files/"+asm_policy_name)
	overview, allowed_responses, raw_policy, raw_general, raw_antivirus, raw_data_guard, raw_login_pages, raw_brute_force_attack_preventions = get_overview(bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"overview.txt", "w") as outfile:
		json.dump(overview, outfile)
	with open("config_files/"+asm_policy_name+"/"+"allowed_responses.txt", "w") as outfile:
		json.dump(allowed_responses, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_policy.json", "w") as outfile:
		json.dump(raw_policy, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_general.json", "w") as outfile:
		json.dump(raw_general, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_antivirus.json", "w") as outfile:
		json.dump(raw_antivirus, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_data_guard.json", "w") as outfile:
		json.dump(raw_data_guard, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_login_pages.json", "w") as outfile:
		json.dump(raw_login_pages, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_brute_force_attack_preventions.json", "w") as outfile:
		json.dump(raw_brute_force_attack_preventions, outfile)				
	########################  File Types  ########################
	file_types_allowed, file_types_disallowed, raw_filetypes = get_file_types(bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"file_types_allowed.txt", "w") as outfile:
		json.dump(file_types_allowed, outfile)
	with open("config_files/"+asm_policy_name+"/"+"file_types_disallowed.txt", "w") as outfile:
		json.dump(file_types_disallowed, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_filetypes.json", "w") as outfile:
		json.dump(raw_filetypes, outfile)
	########################   URLs  ########################
	urls, raw_url = get_urls(bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"urls.txt", "w") as outfile:
		json.dump(urls, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_url.json", "w") as outfile:
		json.dump(raw_url, outfile)
	########################   Parameters  ########################
	parameters, raw_parameters = get_parameters (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"parameters.txt", "w") as outfile:
		json.dump(parameters, outfile)			
	with open("config_files/"+asm_policy_name+"/"+"raw_parameters.json", "w") as outfile:
		json.dump(raw_parameters, outfile)		
	########################   Signatures overview ########################
	signatures_overview, raw_signatures, raw_signature_settings, raw_signature_statuses = get_signatures_overview (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"signatures_overview.txt", "w") as outfile:
		json.dump(signatures_overview, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_signatures.json", "w") as outfile:
		json.dump(raw_signatures, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_signature_settings.json", "w") as outfile:
		json.dump(raw_signature_settings, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_signature_statuses.json", "w") as outfile:
		json.dump(raw_signature_statuses, outfile)
	########################   Signature Sets  ########################
	signature_sets,raw_signature_sets = get_signature_sets (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"signature_sets.txt", "w") as outfile:
		json.dump(signature_sets, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_signature_sets.json", "w") as outfile:
		json.dump(raw_signature_sets, outfile)
	###################		  	 Methods 		########################
	methods, raw_methods = get_methods (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"methods.txt", "w") as outfile:
		json.dump(methods, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_methods.json", "w") as outfile:
		json.dump(raw_methods, outfile)
	########################   Headers  ########################
	headers,raw_headers = get_headers (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"headers.txt", "w") as outfile:
		json.dump(headers, outfile)	
	with open("config_files/"+asm_policy_name+"/"+"raw_headers.json", "w") as outfile:
		json.dump(raw_headers, outfile)	
	########################   Cookies  ########################
	cookies, raw_cookies = get_cookies (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"cookies.txt", "w") as outfile:
		json.dump(cookies, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_cookies.json", "w") as outfile:
		json.dump(raw_cookies, outfile)
	########################   Redirection Domains  ########################
	domains, raw_redirection_protection_domains = get_domains (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"domains.txt", "w") as outfile:
		json.dump(domains, outfile)				
	with open("config_files/"+asm_policy_name+"/"+"raw_redirection_protection_domains.json", "w") as outfile:
		json.dump(raw_redirection_protection_domains, outfile)		
	########################   IP Intelligence  ########################
	ipi, ipi_categories,raw_ip_intelligence = get_ipi (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"ipi.txt", "w") as outfile:
		json.dump(ipi, outfile)			
	with open("config_files/"+asm_policy_name+"/"+"ipi_categories.txt", "w") as outfile:
		json.dump(ipi_categories, outfile)		
	with open("config_files/"+asm_policy_name+"/"+"raw_ip_intelligence.json", "w") as outfile:
		json.dump(raw_ip_intelligence, outfile)		
	########################   Blocking Settings  ########################
	blocking_settings, raw_blocking_violations = get_blocking_settings (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"blocking_settings.txt", "w") as outfile:
		json.dump(blocking_settings, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_blocking_violations.json", "w") as outfile:
		json.dump(raw_blocking_violations, outfile)
	########################   Compliance Settings  ########################
	compliance, raw_blocking_http = get_compliance (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"compliance.txt", "w") as outfile:
		json.dump(compliance, outfile)
	with open("config_files/"+asm_policy_name+"/"+"raw_blocking_http.json", "w") as outfile:
		json.dump(raw_blocking_http, outfile)
	########################   Evasions Settings  ########################
	evasions, raw_evasions = get_evasion (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"evasions.txt", "w") as outfile:
		json.dump(evasions, outfile)			
	with open("config_files/"+asm_policy_name+"/"+"raw_evasions.json", "w") as outfile:
		json.dump(raw_evasions, outfile)			
	########################   WhiteList IPs ########################
	whitelist, raw_whitelist = get_whitelist (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"whitelist.txt", "w") as outfile:
		json.dump(whitelist, outfile)		
	with open("config_files/"+asm_policy_name+"/"+"raw_whitelist.json", "w") as outfile:
		json.dump(raw_whitelist, outfile)		
	########################   Policy Builder  ########################
	policy_builder = get_policy_builder (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"policy_builder.txt", "w") as outfile:
		json.dump(policy_builder, outfile)

	########################   CSRF URLs  ########################
	raw_csrf_urls = get_csrf_urls (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"raw_csrf_urls.json", "w") as outfile:
		json.dump(raw_csrf_urls, outfile)
	########################   History_revisions  ########################
	raw_history_revisions = get_history_revisions (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"raw_history_revisions.json", "w") as outfile:
		json.dump(raw_history_revisions, outfile)							
	
	########################   Web Scapring  ########################
#	raw_web_scraping = get_web_scraping (bigip, asm_policy_id, username, password)
#	with open("config_files/"+asm_policy_name+"/"+"raw_web_scraping.json", "w") as outfile:
#		json.dump(raw_web_scraping, outfile)							
	
	########################   CSRF Protection  ########################
	raw_csrf_protection = get_csrf_protection (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"raw_csrf_protection.json", "w") as outfile:
		json.dump(raw_csrf_protection, outfile)							
	
	########################   SESSION_TRACKING  ########################
	session_tracking, raw_session_tracking = get_session_tracking (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"session_tracking.txt", "w") as outfile:
		json.dump(session_tracking, outfile)				
	with open("config_files/"+asm_policy_name+"/"+"raw_session_tracking.json", "w") as outfile:
		json.dump(raw_session_tracking, outfile)
		
	########################  REDIRECTION PROTECTION   ########################
	raw_redirection_protection = get_redirection_protection (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"raw_redirection_protection.json", "w") as outfile:
		json.dump(raw_redirection_protection, outfile)

	########################   RESPONSE PAGE  ########################
	response_pages, raw_response_pages =  get_response_pages(bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"response_pages.txt", "w") as outfile:
		json.dump(response_pages, outfile)												
	with open("config_files/"+asm_policy_name+"/"+"raw_response_pages.json", "w") as outfile:
		json.dump(raw_response_pages, outfile)	
		
	########################   PALIN TEXT  ########################
	raw_plain_text_profiles =  get_plain_text_profiles(bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"raw_plain_text_profiles.json", "w") as outfile:
		json.dump(raw_plain_text_profiles, outfile)										
		
	######################## SERVER TECHNOLOGIES    ########################
	raw_server_technologies =  get_server_technologies(bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"raw_server_technologies.json", "w") as outfile:
		json.dump(raw_server_technologies, outfile)										
	
	########################   raw_sensitive_parameters  ########################
	sensitive_param, raw_sensitive_parameters =  get_sensitive_parameters(bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"sensitive_param.txt", "w") as outfile:
		json.dump(sensitive_param, outfile)	
	with open("config_files/"+asm_policy_name+"/"+"raw_sensitive_parameters.json", "w") as outfile:
		json.dump(raw_sensitive_parameters, outfile)										
	
	#######################   raw_character_sets  ########################
	raw_character_sets =  get_character_sets(bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"raw_character_sets.json", "w") as outfile:
		json.dump(raw_character_sets, outfile)										
	
	########################  raw_json_profiles   ########################
	raw_json_profiles = get_json_profiles (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"raw_json_profiles.json", "w") as outfile:
		json.dump(raw_json_profiles, outfile)									
		
	######################## raw_xml_profiles ########################
	raw_xml_profiles = get_xml_profiles (bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"raw_xml_profiles.json", "w") as outfile:
		json.dump(raw_xml_profiles, outfile)										
		
	########################  DISALLOWED LOCATION   ########################
	disallowed_geolocations =  get_disallowed_geolocations(bigip, asm_policy_id, username, password)
	with open("config_files/"+asm_policy_name+"/"+"disallowed_geolocations.txt", "w") as outfile:
		json.dump(disallowed_geolocations, outfile)										

	########################  Suggestions ########################
	suggestions, results = analyze_policy (overview, allowed_responses, file_types_allowed, urls, parameters, signatures_overview, signature_sets, methods, headers, cookies, domains, ipi, ipi_categories, blocking_settings, compliance, evasions, whitelist, policy_builder, sensitive_param)	
#	with open("config_files/"+asm_policy_name+"/"+"suggestions.txt", "w") as outfile:
#		json.dump(suggestions, outfile)
	with open("config_files/"+asm_policy_name+"/"+"results.txt", "w") as outfile:
		json.dump(results, outfile)				