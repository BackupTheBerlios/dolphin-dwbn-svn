package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * TopMenu generated by hbm2java
 */
@Entity
@Table(name = "TopMenu", catalog = "dolphin")
public class TopMenu implements java.io.Serializable {

	private Integer id;
	private int parent;
	private String name;
	private String caption;
	private String link;
	private int order;
	private String visible;
	private String target;
	private String onclick;
	private String check;
	private boolean editable;
	private boolean deletable;
	private boolean active;
	private String type;
	private boolean strict;

	public TopMenu() {
	}

	public TopMenu(int parent, String name, String caption, String link,
			int order, String visible, String target, String onclick,
			String check, boolean editable, boolean deletable, boolean active,
			String type, boolean strict) {
		this.parent = parent;
		this.name = name;
		this.caption = caption;
		this.link = link;
		this.order = order;
		this.visible = visible;
		this.target = target;
		this.onclick = onclick;
		this.check = check;
		this.editable = editable;
		this.deletable = deletable;
		this.active = active;
		this.type = type;
		this.strict = strict;
	}

	@Id
	@GeneratedValue(strategy = IDENTITY)
	@Column(name = "ID", unique = true, nullable = false)
	public Integer getId() {
		return this.id;
	}

	public void setId(Integer id) {
		this.id = id;
	}

	@Column(name = "Parent", nullable = false)
	public int getParent() {
		return this.parent;
	}

	public void setParent(int parent) {
		this.parent = parent;
	}

	@Column(name = "Name", nullable = false, length = 50)
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

	@Column(name = "Link", nullable = false)
	public String getLink() {
		return this.link;
	}

	public void setLink(String link) {
		this.link = link;
	}

	@Column(name = "Order", nullable = false)
	public int getOrder() {
		return this.order;
	}

	public void setOrder(int order) {
		this.order = order;
	}

	@Column(name = "Visible", nullable = false, length = 10)
	public String getVisible() {
		return this.visible;
	}

	public void setVisible(String visible) {
		this.visible = visible;
	}

	@Column(name = "Target", nullable = false, length = 20)
	public String getTarget() {
		return this.target;
	}

	public void setTarget(String target) {
		this.target = target;
	}

	@Column(name = "Onclick", nullable = false, length = 16777215)
	public String getOnclick() {
		return this.onclick;
	}

	public void setOnclick(String onclick) {
		this.onclick = onclick;
	}

	@Column(name = "Check", nullable = false)
	public String getCheck() {
		return this.check;
	}

	public void setCheck(String check) {
		this.check = check;
	}

	@Column(name = "Editable", nullable = false)
	public boolean isEditable() {
		return this.editable;
	}

	public void setEditable(boolean editable) {
		this.editable = editable;
	}

	@Column(name = "Deletable", nullable = false)
	public boolean isDeletable() {
		return this.deletable;
	}

	public void setDeletable(boolean deletable) {
		this.deletable = deletable;
	}

	@Column(name = "Active", nullable = false)
	public boolean isActive() {
		return this.active;
	}

	public void setActive(boolean active) {
		this.active = active;
	}

	@Column(name = "Type", nullable = false, length = 7)
	public String getType() {
		return this.type;
	}

	public void setType(String type) {
		this.type = type;
	}

	@Column(name = "Strict", nullable = false)
	public boolean isStrict() {
		return this.strict;
	}

	public void setStrict(boolean strict) {
		this.strict = strict;
	}

}
