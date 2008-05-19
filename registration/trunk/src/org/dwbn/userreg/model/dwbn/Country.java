package org.dwbn.userreg.model.dwbn;

import javax.persistence.Entity;
import javax.persistence.Id;

@Entity
public class Country {
	@Id
	private String name;

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}
	
	public String toString() {
		return name;
	}
}
