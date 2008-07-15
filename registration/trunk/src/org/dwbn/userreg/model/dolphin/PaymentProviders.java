package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * PaymentProviders generated by hbm2java
 */
@Entity
@Table(name = "PaymentProviders", catalog = "dolphin")
public class PaymentProviders implements java.io.Serializable {

	private Short id;
	private String name;
	private String caption;
	private boolean active;
	private String mode;
	private boolean debug;
	private String checkoutFilename;
	private String checkoutUrl;
	private boolean supportsRecurring;
	private String logoFilename;
	private String help;

	public PaymentProviders() {
	}

	public PaymentProviders(String name, String caption, boolean active,
			String mode, boolean debug, String checkoutFilename,
			String checkoutUrl, boolean supportsRecurring) {
		this.name = name;
		this.caption = caption;
		this.active = active;
		this.mode = mode;
		this.debug = debug;
		this.checkoutFilename = checkoutFilename;
		this.checkoutUrl = checkoutUrl;
		this.supportsRecurring = supportsRecurring;
	}

	public PaymentProviders(String name, String caption, boolean active,
			String mode, boolean debug, String checkoutFilename,
			String checkoutUrl, boolean supportsRecurring, String logoFilename,
			String help) {
		this.name = name;
		this.caption = caption;
		this.active = active;
		this.mode = mode;
		this.debug = debug;
		this.checkoutFilename = checkoutFilename;
		this.checkoutUrl = checkoutUrl;
		this.supportsRecurring = supportsRecurring;
		this.logoFilename = logoFilename;
		this.help = help;
	}

	@Id
	@GeneratedValue(strategy = IDENTITY)
	@Column(name = "ID", unique = true, nullable = false)
	public Short getId() {
		return this.id;
	}

	public void setId(Short id) {
		this.id = id;
	}

	@Column(name = "Name", nullable = false, length = 30)
	public String getName() {
		return this.name;
	}

	public void setName(String name) {
		this.name = name;
	}

	@Column(name = "Caption", nullable = false, length = 50)
	public String getCaption() {
		return this.caption;
	}

	public void setCaption(String caption) {
		this.caption = caption;
	}

	@Column(name = "Active", nullable = false)
	public boolean isActive() {
		return this.active;
	}

	public void setActive(boolean active) {
		this.active = active;
	}

	@Column(name = "Mode", nullable = false, length = 12)
	public String getMode() {
		return this.mode;
	}

	public void setMode(String mode) {
		this.mode = mode;
	}

	@Column(name = "Debug", nullable = false)
	public boolean isDebug() {
		return this.debug;
	}

	public void setDebug(boolean debug) {
		this.debug = debug;
	}

	@Column(name = "CheckoutFilename", nullable = false)
	public String getCheckoutFilename() {
		return this.checkoutFilename;
	}

	public void setCheckoutFilename(String checkoutFilename) {
		this.checkoutFilename = checkoutFilename;
	}

	@Column(name = "CheckoutURL", nullable = false)
	public String getCheckoutUrl() {
		return this.checkoutUrl;
	}

	public void setCheckoutUrl(String checkoutUrl) {
		this.checkoutUrl = checkoutUrl;
	}

	@Column(name = "SupportsRecurring", nullable = false)
	public boolean isSupportsRecurring() {
		return this.supportsRecurring;
	}

	public void setSupportsRecurring(boolean supportsRecurring) {
		this.supportsRecurring = supportsRecurring;
	}

	@Column(name = "LogoFilename", length = 100)
	public String getLogoFilename() {
		return this.logoFilename;
	}

	public void setLogoFilename(String logoFilename) {
		this.logoFilename = logoFilename;
	}

	@Column(name = "Help", length = 65535)
	public String getHelp() {
		return this.help;
	}

	public void setHelp(String help) {
		this.help = help;
	}

}