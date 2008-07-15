package org.dwbn.userreg.service;

import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.Reader;
import java.io.StringReader;
import java.io.StringWriter;
import java.net.HttpURLConnection;
import java.net.URL;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBElement;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Unmarshaller;
import javax.xml.transform.stream.StreamSource;


public class NingService {

	private String login;
	private String password;

	public static final String PHOTOS = "Photo";
	public static final String VIDEO = "Video";
	public static final String TOPIC = "Topic";
	public static final String BLOGPOST = "BlogPost";

	public NingService(String username, String password) {
		this.login = username;
		this.password = password;
	}

//	public void readContent(String type) throws JAXBException, IOException {
//		Unmarshaller marshal = JAXBContext.newInstance(
//				FeedType.class.getPackage().getName()).createUnmarshaller();
//		marshal
//				.setEventHandler(new javax.xml.bind.helpers.DefaultValidationEventHandler());
//		marshal.setSchema(null);
//		// Authenticator.setDefault(new Authenticator() {
//		// protected PasswordAuthentication getPasswordAuthentication() {
//		// return new PasswordAuthentication(login, password.toCharArray());
//		// }
//		// });
//
//		URL url = new URL(getUrl(type));
//		HttpURLConnection conn = (HttpURLConnection) url.openConnection();
//		conn.setRequestMethod("GET");
//
//		conn.setUseCaches(false);
//		if (conn.getResponseCode() != HttpURLConnection.HTTP_OK) {
//			System.out.println(conn.getResponseMessage());
//		} else {
//			String s = readStream(conn.getInputStream());
//			System.out.println("-" + s + "-");
//			Reader reader = new StringReader(s);
//			StreamSource source = new StreamSource(reader);
//			JAXBElement<FeedType> feedjax = marshal.unmarshal(source,
//					FeedType.class);
//			FeedType feed = feedjax.getValue();
//			System.out.println(feed.getId());
//		}
//	}
//
//	public static void main(String[] args) throws JAXBException, IOException {
//		NingService ning = new NingService("a.boehlke@gmail.com", "alifalafel");
//		// ning.read();
//	}
//
//	public void readFriends() {
//		 String url = "http://virtualsangha.ning.com/xn/rest/1.0/profile:StephanG/contact?begin=0&end=2000";
//	}
//	
//	private String getUrl(String type) {
//		return "http://apitest.ning.com/back.php?username=" + login	+ "&password=" + password + "&ctype=" + type;
//	}
//
//	private static String readStream(InputStream input) throws IOException {
//		char[] buffer = new char[8072];
//		Reader in = new InputStreamReader(input);
//		StringWriter sw = new StringWriter();
//		int charsRead;
//		while ((charsRead = in.read(buffer)) != -1) {
//			sw.write(buffer, 0, charsRead);
//		}
//		return sw.toString().replaceAll("my:", "my_").replaceAll("xn:", "xn_")
//				.replaceAll("xmlns.*=\".+\"", "");
//	}

}
