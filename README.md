# asm_audit
> A tool to perform a basic audit on F5 ASM policies

**ASM versions tested**

- v13.1
- v14.1
- v15.1 (soon)



[![INSERT YOUR GRAPHIC HERE](https://github.com/skenderidis/asm_audit/blob/master/images/asm_audit_1.png?raw=true)]()

---

## Installation
The Audit tool runs as a docker container. 

```shell
$ docker run -dit -p 80:80 skenderidis/asm_audit
```

> To get started...

### Step 1
Import the ASM policies you want to audit from the F5 device.

[![INSERT YOUR GRAPHIC HERE](https://github.com/skenderidis/asm_audit/blob/master/images/asm_audit_2.png?raw=true)]()


### Step 2
Review the recommendations for the  policy you have analysed 

[![INSERT YOUR GRAPHIC HERE](https://github.com/skenderidis/asm_audit/blob/master/images/asm_audit_3.png?raw=true)]()


### Step 3 (Optional)
Analyze the policy again based on different security controls  

[![INSERT YOUR GRAPHIC HERE](https://github.com/skenderidis/asm_audit/blob/master/images/asm_audit_4.png?raw=true)]()

---


