<?php
namespace core\backend\network\http;

/**
 * http_status_code short summary.
 *
 * http_status_code description.
 *
 * @version 1.0
 * @author la_ma
 */

abstract class status_code
{

    //Informational responses
    const can_continue = 100;
    const switch_protocols = 101;
    const processing = 102;

    //Successful request
    const ok = 200; //Standard response for successful HTTP requests. The actual response will depend on the request method used. In a GET request, the response will contain an entity corresponding to the requested resource. In a POST request, the response will contain an entity describing or containing the result of the action.
    const created = 201; //The request has been fulfilled, resulting in the creation of a new resource.
    const accepted = 202; //The request has been accepted for processing, but the processing has not been completed. The request might or might not be eventually acted upon, and may be disallowed when processing occurs.
    const non_authoritative_information = 203; //(since HTTP/1.1) The server is a transforming proxy (e.g. a Web accelerator) that received a 200 OK from its origin, but is returning a modified version of the origin's response.
    const no_content = 204; //The server successfully processed the request and is not returning any content.
    const reset_content = 205; //The server successfully processed the request, but is not returning any content. Unlike a 204 response, this response requires that the requester reset the document view.[13]
    const partial_content = 206; //(RFC 7233) The server is delivering only part of the resource (byte serving) due to a range header sent by the client. The range header is used by HTTP clients to enable resuming of interrupted downloads, or split a download into multiple simultaneous streams.[14]
    const multi_status = 207; //(WebDAV; RFC 4918) The message body that follows is an XML message and can contain a number of separate response codes, depending on how many sub-requests were made.[15]
    const already_reported = 208; //(WebDAV; RFC 5842) The members of a DAV binding have already been enumerated in a previous reply to this request, and are not being included again.[16]
    const im_used = 226; //(RFC 3229) The server has fulfilled a request for the resource, and the response is a representation of the result of one or more instance-manipulations applied to the current instance

    //3xx Redirection
    const multiple_choices = 300; //Indicates multiple options for the resource from which the client may choose (via agent-driven content negotiation). For example, this code could be used to present multiple video format options, to list files with different filename extensions, or to suggest word-sense disambiguation.
    const moved_permanently = 301; //This and all future requests should be directed to the given URI.
    const found = 302; //This is an example of industry practice contradicting the standard. The HTTP/1.0 specification (RFC 1945) required the client to perform a temporary redirect (the original describing phrase was "Moved Temporarily"),[21] but popular browsers implemented 302 with the functionality of a 303 See Other. Therefore, HTTP/1.1 added status codes 303 and 307 to distinguish between the two behaviours. However, some Web applications and frameworks use the 302 status code as if it were the 303.
    const see_other = 303; //(since HTTP/1.1) The response to the request can be found under another URI using a GET method. When received in response to a POST (or PUT/DELETE), the client should presume that the server has received the data and should issue a redirect with a separate GET message.
    const not_modified = 304; //(RFC 7232) Indicates that the resource has not been modified since the version specified by the request headers If-Modified-Since or If-None-Match. In such case, there is no need to retransmit the resource since the client still has a previously-downloaded copy.
    const use_proxy = 305; //(since HTTP/1.1) The requested resource is available only through a proxy, the address for which is provided in the response. Many HTTP clients (such as Mozilla and Internet Explorer) do not correctly handle responses with this status code, primarily for security reasons.
    const switch_proxy = 306; //No longer used. Originally meant "Subsequent requests should use the specified proxy."
    const temporary_redirect = 307; //(since HTTP/1.1) In this case, the request should be repeated with another URI; however, future requests should still use the original URI. In contrast to how 302 was historically implemented, the request method is not allowed to be changed when reissuing the original request. For example, a POST request should be repeated using another POST request.
    const permanent_redirect = 308; //(RFC 7538) The request and all future requests should be repeated using another URI. 307 and 308 parallel the behaviors of 302 and 301, but do not allow the HTTP method to change. So, for example, submitting a form to a permanently redirected resource may continue smoothly.

    //4xx Client errors
    const bad_request = 400; //The server cannot or will not process the request due to an apparent client error (e.g., malformed request syntax, too large size, invalid request message framing, or deceptive request routing).
    const unauthorized = 401; //Similar to 403 Forbidden, but specifically for use when authentication is required and has failed or has not yet been provided. The response must include a WWW-Authenticate header field containing a challenge applicable to the requested resource. See Basic access authentication and Digest access authentication. 401 semantically means "unauthenticated", i.e. the user does not have the necessary credentials.
    const payment_required = 402; //Reserved for future use. The original intention was that this code might be used as part of some form of digital cash or micropayment scheme, but that has not happened, and this code is not usually used. Google Developers API uses this status if a particular developer has exceeded the daily limit on requests.
    const forbidden = 403; //The request was valid, but the server is refusing action. The user might not have the necessary permissions for a resource.
    const not_found = 404; //The requested resource could not be found but may be available in the future. Subsequent requests by the client are permissible.
    const method_not_allowed = 405; //A request method is not supported for the requested resource; for example, a GET request on a form that requires data to be presented via POST, or a PUT request on a read-only resource.
    const not_acceptable = 406; //The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.
    const proxy_authentication_required = 407; //(RFC 7235) The client must first authenticate itself with the proxy.[38]
    const request_time_out = 408; //The server timed out waiting for the request. According to HTTP specifications: "The client did not produce a request within the time that the server was prepared to wait. The client MAY repeat the request without modifications at any later time."[39]
    const conflict = 409; //Indicates that the request could not be processed because of conflict in the request, such as an edit conflict between multiple simultaneous updates.
    const gone = 410; //Indicates that the resource requested is no longer available and will not be available again. This should be used when a resource has been intentionally removed and the resource should be purged. Upon receiving a 410 status code, the client should not request the resource in the future. Clients such as search engines should remove the resource from their indices.[40] Most use cases do not require clients and search engines to purge the resource, and a "404 Not Found" may be used instead.
    const length_required = 411; //The request did not specify the length of its content, which is required by the requested resource.[41]
    const precondition_failed = 412; //(RFC 7232) The server does not meet one of the preconditions that the requester put on the request.[42]
    const payload_too_large = 413; //(RFC 7231) The request is larger than the server is willing or able to process. Previously called "Request Entity Too Large".[43]
    const uri_too_long = 414; //(RFC 7231) The URI provided was too long for the server to process. Often the result of too much data being encoded as a query-string of a GET request, in which case it should be converted to a POST request.[44] Called "Request-URI Too Long" previously.[45]
    const unsupported_media_type = 415; //The request entity has a media type which the server or resource does not support. For example, the client uploads an image as image/svg+xml, but the server requires that images use a different format.
    const range_not_satisfiable = 416; //(RFC 7233) The client has asked for a portion of the file (byte serving), but the server cannot supply that portion. For example, if the client asked for a part of the file that lies beyond the end of the file.[46] Called "Requested Range Not Satisfiable" previously.[47]
    const expectation_failed = 417; //The server cannot meet the requirements of the Expect request-header field.[48]
    const im_a_teapot = 418; //(RFC 2324) This code was defined in 1998 as one of the traditional IETF April Fools' jokes, in RFC 2324, Hyper Text Coffee Pot Control Protocol, and is not expected to be implemented by actual HTTP servers. The RFC specifies this code should be returned by teapots requested to brew coffee.[49] This HTTP status is used as an Easter egg in some websites, including Google.com.[50]
    const misdirected_request = 421; //(RFC 7540) The request was directed at a server that is not able to produce a response (for example because a connection reuse).[51]
    const unprocessable_entity = 422; //(WebDAV; RFC 4918) The request was well-formed but was unable to be followed due to semantic errors.[15]
    const locked = 423; //(WebDAV; RFC 4918) The resource that is being accessed is locked.[15]
    const failed_dependency = 424; //(WebDAV; RFC 4918) The request failed due to failure of a previous request (e.g., a PROPPATCH).[15]
    const upgrade_required = 426; //The client should switch to a different protocol such as TLS/1.0, given in the Upgrade header field.[52]
    const precondition_required = 428; //(RFC 6585) The origin server requires the request to be conditional. Intended to prevent "the 'lost update' problem, where a client GETs a resource's state, modifies it, and PUTs it back to the server, when meanwhile a third party has modified the state on the server, leading to a conflict."[53]
    const too_many_requests = 429; //(RFC 6585) The user has sent too many requests in a given amount of time. Intended for use with rate-limiting schemes.[53]
    const request_header_fields_too_large = 431; //(RFC 6585) The server is unwilling to process the request because either an individual header field, or all the header fields collectively, are too large.[53]
    const unavailable_for_legal_reasons = 451; //(RFC 7725) A server operator has received a legal demand to deny access to a resource or to a set of resources that includes the requested resource.[54] The code 451 was chosen as a reference to the novel Fahrenheit 451.

    //5xx server errors
    const internal_server_error = 500; //A generic error message, given when an unexpected condition was encountered and no more specific message is suitable.[57]
    const not_implemented = 501; //The server either does not recognize the request method, or it lacks the ability to fulfill the request. Usually this implies future availability (e.g., a new feature of a web-service API).[58]
    const bad_gateway = 502; //The server was acting as a gateway or proxy and received an invalid response from the upstream server.[59]
    const service_unavailable = 503; //The server is currently unavailable (because it is overloaded or down for maintenance). Generally, this is a temporary state.[60]
    const gateway_time_out = 504; //The server was acting as a gateway or proxy and did not receive a timely response from the upstream server.[61]
    const http_version_not_supported = 505; //The server does not support the HTTP protocol version used in the request.[62]
    const variant_also_negotiates = 506; //(RFC 2295) Transparent content negotiation for the request results in a circular reference.[63]
    const insufficient_storage = 507; //(WebDAV; RFC 4918) The server is unable to store the representation needed to complete the request.[15]
    const loop_detected = 508; //(WebDAV; RFC 5842) The server detected an infinite loop while processing the request (sent in lieu of 208 Already Reported).
    const not_extended = 510; //(RFC 2774) Further extensions to the request are required for the server to fulfill it.[64]
    const network_authentication_required = 511; //(RFC 6585) The client needs to authenticate to gain network access. Intended for use by intercepting proxies used to control access to the network (e.g., "captive portals" used to require agreement to Terms of Service before granting full Internet access via a Wi-Fi hotspot).[53]

    //Unofficial codes
    const checkpoint = 103; //Used in the resumable requests proposal to resume aborted PUT or POST requests.
    const early_hints = 103; //Used to return some response headers before entire HTTP response.
    const method_failure = 420; //A deprecated response used by the Spring Framework when a method has failed.
    const enhance_your_calm = 420; //Returned by version 1 of the Twitter Search and Trends API when the client is being rate limited; versions 1.1 and later use the 429 Too Many Requests response code instead.
    const blocked_by_windows_parental_controls = 450; //The Microsoft extension code indicated when Windows Parental Controls are turned on and are blocking access to the given webpage.
    const invalid_token = 498; //Returned by ArcGIS for Server. Code 498 indicates an expired or otherwise invalid token.
    const token_required = 499; //Returned by ArcGIS for Server. Code 499 indicates that a token is required but was not submitted.
    const bandwidth_limit_exceeded = 509; //The server has exceeded the bandwidth specified by the server administrator; this is often used by shared hosting providers to limit the bandwidth of customers.
    const site_is_frozen = 530; //Used by the Pantheon web platform to indicate a site that has been frozen due to inactivity.
    const network_read_timeout_error = 598; //Used by some HTTP proxies to signal a network read timeout behind the proxy to a client in front of the proxy.
    const network_connect_timeout_error = 599; //Used to indicate when the connection to the network times out.

    //Internet Information Services
    const login_time_out = 440; //The client's session has expired and must log in again.
    const retry_with = 449; //The server cannot honour the request because the user has not provided the required information.
    const redirect = 451; //Used in Exchange ActiveSync when either a more efficient server is available or the server cannot access the users' mailbox. The client is expected to re-run the HTTP AutoDiscover operation to find a more appropriate server.

    //Nginx
    const no_response = 444; //Used to indicate that the server has returned no information to the client and closed the connection.
    const ssl_certificate_error = 495; //An expansion of the 400 Bad Request response code, used when the client has provided an invalid client certificate.
    const ssl_certificate_required = 496; //An expansion of the 400 Bad Request response code, used when a client certificate is required but not provided.
    const http_request_sent_to_https_port = 497; //An expansion of the 400 Bad Request response code, used when the client has made a HTTP request to a port listening for HTTPS requests.
    const client_closed_request = 499; //Used when the client has closed the request before the server could send a response.

    //Cloudflare documentation
    const unknown_error =  520; //The 520 error is used as a "catch-all response for when the origin server returns something unexpected", listing connection resets, large headers, and empty or invalid responses as common triggers.
    const web_server_is_down = 521;	//The origin server has refused the connection from Cloudflare.
    const connection_timed_out = 522; //Cloudflare could not negotiate a TCP handshake with the origin server.
    const origin_is_unreachable = 523; //Cloudflare could not reach the origin server; for example, if the DNS records for the origin server are incorrect.
    const a_timeout_occurred = 524; //Cloudflare was able to complete a TCP connection to the origin server, but did not receive a timely HTTP response.
    const ssl_handshake_failed = 525; //Cloudflare could not negotiate a SSL/TLS handshake with the origin server.
    const invalid_ssl_certificate = 526; //Cloudflare could not validate the SSL/TLS certificate that the origin server presented.
    const railgun_error = 527; //Error 527 indicates that the requests timeout or failed after the WAN connection has been established.

    //Cloudproxy
    const firewall_blocked = 403;

    static function is_successful($pstatus_code)
    {
        switch (intval($pstatus_code))
        {
            case self::ok:
                return true;
            case self::created:
                return true;
            case self::accepted:
                return true;
            case self::non_authoritative_information:
                return true;
            case self::no_content:
                return false;
            case self::reset_content:
                return true;
            case self::partial_content:
                return true;
            case self::multi_status:
                return true;
            case self::already_reported:
                return true;
            case self::im_used:
                return true;
            default:
                return false;
        }
    }

    static function is_redirected($pstatus_code)
    {
        switch(intval($pstatus_code))
        {
            case self::moved_permanently:
                return true;
            case self::multiple_choices:
                return true;
            case self::found:
                return true;
            case self::see_other:
                return true;
            case self::not_modified:
                return true;
            case self::use_proxy:
                return true;
            case self::switch_proxy:
                return true;
            case self::temporary_redirect:
                return true;
            case self::permanent_redirect:
                return true;
            default:
                return false;
        }
    }

    static function is_failed($pstatus_code)
    {
        switch (intval($pstatus_code))
        {
            case self::bad_request:
                return true;
            case self::unauthorized:
                return true;
            case self::payment_required:
                return true;
            case self::forbidden:
                return true;
            case self::not_found:
                return true;
            case self::method_not_allowed:
                return true;
            case self::not_acceptable:
                return true;
            case self::proxy_authentication_required:
                return true;
            case self::request_time_out:
                return true;
            case self::conflict:
                return true;
            case self::gone:
                return true;
            case self::length_required:
                return true;
            case self::precondition_failed:
                return true;
            case self::payload_too_large:
                return true;
            case self::uri_too_long:
                return true;
            case self::unsupported_media_type:
                return true;
            case self::range_not_satisfiable:
                return true;
            case self::expectation_failed:
                return true;
            case self::im_a_teapot:
                return true;
            case self::misdirected_request:
                return true;
            case self::unprocessable_entity:
                return true;
            case self::locked:
                return true;
            case self::failed_dependency:
                return true;
            case self::upgrade_required:
                return true;
            case self::precondition_required:
                return true;
            case self::too_many_requests:
                return true;
            case self::request_header_fields_too_large:
                return true;
            case self::unavailable_for_legal_reasons:
                return true;
            default:
                if(self::crashed($pstatus_code)) return true;
                if(self::blocked($pstatus_code)) return true;
                return false;
        }
    }

    static function is_crashed($pstatus_code)
    {
        switch(intval($pstatus_code))
        {
            case self::internal_server_error:
                return true;
            case self::not_implemented:
                return true;
            case self::bad_gateway:
                return true;
            case self::service_unavailable:
                return true;
            case self::gateway_time_out:
                return true;
            case self::http_version_not_supported:
                return true;
            case self::variant_also_negotiates:
                return true;
            case self::insufficient_storage:
                return true;
            case self::loop_detected:
                return true;
            case self::not_extended:
                return true;
            default:
                return false;
        }

    }

    static function is_blocked($pstatus_code)
    {
        switch (intval($pstatus_code))
        {
            case self::unknown_error:
                return true;
            case self::web_server_is_down:
                return true;
            case self::connection_timed_out:
                return true;
            case self::origin_is_unreachable:
                return true;
            case self::a_timeout_occurred:
                return true;
            case self::ssl_handshake_failed:
                return true;
            case self::invalid_ssl_certificate:
                return true;
            case self::railgun_error:
                return true;
            case self::firewall_blocked:
                return true;
            case self::forbidden:
                return true;
            default:
                return false;
        }
    }

    static function is_cloudproxy_blocked($pstatus_code)
    {
        switch (intval($pstatus_code))
        {
            case self::firewall_blocked:
                return true;
            default:
                return false;
        }
    }

    static function is_cloudflare_blocked($pstatus_code)
    {
        switch (intval($pstatus_code))
        {
            case self::unknown_error:
                return true;
            case self::web_server_is_down:
                return true;
            case self::connection_timed_out:
                return true;
            case self::origin_is_unreachable:
                return true;
            case self::a_timeout_occurred:
                return true;
            case self::ssl_handshake_failed:
                return true;
            case self::invalid_ssl_certificate:
                return true;
            case self::railgun_error:
                return true;
            default:
                return false;
        }
    }

}