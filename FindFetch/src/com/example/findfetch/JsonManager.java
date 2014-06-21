package com.example.findfetch;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.client.utils.URLEncodedUtils;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONException;
import org.json.JSONObject;

import android.util.Log;

/**
 * A singleton class to parse things to JSON, 
 * reference: http://www.mybringback.com/tutorial-series/13193/android-mysql-php-json-part-5-developing-the-android-application
 * @author Jun
 *
 */
public class JsonManager {
 
    private static final String BLANK = "";
	private static final String QUESTIONMARK = "?";
	private static final String METHOD_GET = "GET";
	private static final String METHOD_POST = "POST";
	private static final String NEWLINE = "\n";
	private static final String ENCODING_FORMAT = "utf-8";
	private static final String JSON_CHARSET_NAME = "iso-8859-1";
	
	private InputStream _is;
    private JSONObject _jObj;
    private String _json;
    private JsonManager JsonManagerInstance = null;
 
    private JsonManager() {
    	_is = null;
    	_jObj = null;
    	_json = BLANK;
    }
    
    public JsonManager getInstance(){
    	if (JsonManagerInstance == null){
    		JsonManagerInstance = new JsonManager();
    	}
    	
    	return JsonManagerInstance;
    }
    
    
    public JSONObject getJSONFromUrl(final String url) {

        // Making HTTP request
        try {
            // Construct the client and the HTTP request.
            DefaultHttpClient httpClient = new DefaultHttpClient();
            HttpPost httpPost = new HttpPost(url);

            // Execute the POST request and store the response locally.
            HttpResponse httpResponse = httpClient.execute(httpPost);
            // Extract data from the response.
            HttpEntity httpEntity = httpResponse.getEntity();
            // Open an inputStream with the data content.
            _is = httpEntity.getContent();

        } catch (UnsupportedEncodingException e) {
            e.printStackTrace();
        } catch (ClientProtocolException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }

        try {
            int size = 8;

            // Create a BufferedReader to parse through the inputStream.
			BufferedReader reader = new BufferedReader(new InputStreamReader(
                    _is, JSON_CHARSET_NAME), size);
            // Declare a string builder to help with the parsing.
            StringBuilder sb = new StringBuilder();
            // Declare a string to store the JSON object data in string form.
            String line = null;
            
            // Build the string until null.
            while ((line = reader.readLine()) != null) {
                sb.append(line + NEWLINE);
            }
            
            // Close the input stream.
            _is.close();
            // Convert the string builder data to an actual string.
            _json = sb.toString();
        } catch (Exception e) {
            Log.e("Buffer Error", "Error converting result " + e.toString());
        }

        // Try to parse the string to a JSON object
        try {
            _jObj = new JSONObject(_json);
        } catch (JSONException e) {
            Log.e("JsonManager", "Error parsing data " + e.toString());
        }

        // Return the JSON Object.
        return _jObj;

    }
    
 
    // function get json from url
    // by making HTTP POST or GET mehtod
    public JSONObject makeHttpRequest(String url, String method,
            List<NameValuePair> params) {
 
        // Making HTTP request
        try {
 
            // check for request method
            if (METHOD_POST.equals(method)){
                // defaultHttpClient
                DefaultHttpClient httpClient = new DefaultHttpClient();
                HttpPost httpPost = new HttpPost(url);
                httpPost.setEntity(new UrlEncodedFormEntity(params));
 
                HttpResponse httpResponse = httpClient.execute(httpPost);
                HttpEntity httpEntity = httpResponse.getEntity();
                _is = httpEntity.getContent();
 
            } else if (METHOD_GET.equals(method)){
                DefaultHttpClient httpClient = new DefaultHttpClient();
                String queryString = URLEncodedUtils.format(params, ENCODING_FORMAT);
                
                url += QUESTIONMARK + queryString;
                HttpGet httpGet = new HttpGet(url);
 
                HttpResponse httpResponse = httpClient.execute(httpGet);
                HttpEntity httpEntity = httpResponse.getEntity();
                _is = httpEntity.getContent();
            } else {
            	//fails due to code error
            	assert(false);
            }
 
        } catch (UnsupportedEncodingException e) {
            e.printStackTrace();
        } catch (ClientProtocolException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
 
        try {
            int size = 8;
			BufferedReader reader = new BufferedReader(new InputStreamReader(
                    _is, JSON_CHARSET_NAME), size);
            StringBuilder sb = new StringBuilder();
            String line = null;
            while ((line = reader.readLine()) != null) {
                sb.append(line + NEWLINE);
            }
            _is.close();
            _json = sb.toString();
        } catch (Exception e) {
            Log.e("Buffer Error", "Error converting result " + e.toString());
        }
 
        // try parse the string to a JSON object
        try {
            _jObj = new JSONObject(_json);
        } catch (JSONException e) {
            Log.e("JSON Parser", "Error parsing data " + e.toString());
        }
 
        // return JSON String
        return _jObj;
 
    }
}
